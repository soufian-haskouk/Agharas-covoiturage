from flask import Flask, request, jsonify
from flask_cors import CORS
import pandas as pd
import numpy as np
from sklearn.metrics.pairwise import cosine_similarity
from sklearn.preprocessing import StandardScaler
from sklearn.feature_extraction.text import CountVectorizer
import mysql.connector
import json
from datetime import datetime, date, time

app = Flask(__name__)
CORS(app)

# Configuration de la base de données
db_config = {
    'host': '127.0.0.1',
    'user': 'root',
    'password': '',
    'database': 'agharas_bd'
}

def get_db_connection():
    return mysql.connector.connect(**db_config)

def convert_to_serializable(obj):
    """Convertit les objets non sérialisables en types JSON compatibles"""
    if isinstance(obj, (datetime, date)):
        return obj.isoformat()
    elif isinstance(obj, time):
        return obj.strftime('%H:%M')
    elif isinstance(obj, np.integer):
        return int(obj)
    elif isinstance(obj, np.floating):
        return float(obj)
    elif isinstance(obj, np.ndarray):
        return obj.tolist()
    elif pd.isna(obj):
        return None
    else:
        return obj

class SimpleRecommendationEngine:
    def __init__(self):
        self.data_path = 'training_data/'
        self.load_data()
    
    def load_data(self):
        """Charge les données préparées"""
        print("📂 Chargement des données...")
        self.interactions = pd.read_csv(f'{self.data_path}/user_ride_interactions.csv')
        self.rides = pd.read_csv(f'{self.data_path}/available_rides.csv')
        print(f"✅ Données chargées: {len(self.interactions)} interactions, {len(self.rides)} trajets")
    
    def recommend_for_user(self, user_id, top_n=5):
        """Génère des recommandations simples pour un utilisateur"""
        print(f"🎯 Recherche de recommandations pour l'utilisateur {user_id}...")
        
        # Vérifier si l'utilisateur existe dans les interactions
        user_interactions = self.interactions[self.interactions['user_id'] == user_id]
        
        if len(user_interactions) == 0:
            # Nouvel utilisateur: retourner les trajets populaires
            popular_rides = self.interactions['ride_id'].value_counts().head(top_n).index.tolist()
            print("👤 Nouvel utilisateur - recommandations populaires")
            return popular_rides
        else:
            # Utilisateur existant: basé sur son historique
            user_departures = user_interactions['depart'].unique()
            user_destinations = user_interactions['arrive'].unique()
            
            # Filtrer les trajets similaires
            recommended = self.rides[
                (self.rides['depart'].isin(user_departures)) | 
                (self.rides['arrive'].isin(user_destinations))
            ]
            
            # Exclure les trajets déjà pris
            taken_rides = user_interactions['ride_id'].tolist()
            recommended = recommended[~recommended['ride_id'].isin(taken_rides)]
            
            recommended_rides = recommended.head(top_n)['ride_id'].tolist()
            
            # Si pas assez, ajouter des trajets populaires
            if len(recommended_rides) < top_n:
                popular_rides = self.interactions['ride_id'].value_counts().head(top_n).index.tolist()
                additional = [r for r in popular_rides if r not in recommended_rides]
                recommended_rides.extend(additional[:top_n - len(recommended_rides)])
            
            print(f"✅ {len(recommended_rides)} recommandations trouvées")
            return recommended_rides

# Initialiser le moteur
engine = SimpleRecommendationEngine()

@app.route('/')
def home():
    return jsonify({
        'message': 'API de Recommandation Agharas',
        'endpoints': {
            '/health': 'Vérifier le statut de l\'API',
            '/recommend?user_id=X': 'Obtenir des recommandations'
        }
    })

@app.route('/health', methods=['GET'])
def health():
    return jsonify({
        'status': 'healthy', 
        'message': 'API de recommandation Agharas opérationnelle',
        'data_stats': {
            'interactions': len(engine.interactions),
            'rides': len(engine.rides),
            'users': engine.interactions['user_id'].nunique()
        }
    })

@app.route('/recommend', methods=['GET'])
def recommend():
    """Endpoint pour obtenir des recommandations"""
    user_id = request.args.get('user_id', type=int)
    
    if not user_id:
        return jsonify({'error': 'user_id est requis'}), 400
    
    try:
        # Générer les recommandations
        recommended_ride_ids = engine.recommend_for_user(user_id, top_n=5)
        
        # Récupérer les détails des trajets depuis la base de données
        conn = get_db_connection()
        cursor = conn.cursor(dictionary=True)
        
        if recommended_ride_ids:
            placeholders = ','.join(['%s'] * len(recommended_ride_ids))
            query = f"""
                SELECT 
                    t.id,
                    t.depart,
                    t.arrive,
                    DATE(t.date_trajet) as date_trajet,
                    TIME_FORMAT(t.heure_depart, '%H:%i') as heure_depart,  # Enlever un %
                    t.prix,
                    t.places_disponibles,
                    t.car,
                    t.conducteur_id,
                    u.nom, 
                    u.prenom 
                FROM trajet t 
                JOIN users u ON t.conducteur_id = u.id 
                WHERE t.id IN ({placeholders}) 
                AND t.status = 'active'
                AND t.places_disponibles > 0
                AND t.date_trajet >= CURDATE()
                ORDER BY FIELD(t.id, {placeholders})
            """
            # Dupliquer les placeholders pour le ORDER BY FIELD
            params = recommended_ride_ids + recommended_ride_ids
            cursor.execute(query, params)
            rides = cursor.fetchall()
            
            # Convertir tous les objets en types sérialisables
            for ride in rides:
                for key, value in ride.items():
                    ride[key] = convert_to_serializable(value)
        else:
            rides = []
        
        cursor.close()
        conn.close()
        
        return jsonify({
            'success': True,
            'user_id': user_id,
            'recommended_rides': rides,
            'count': len(rides)
        })
        
    except Exception as e:
        print(f"❌ Erreur: {str(e)}")
        return jsonify({
            'success': False,
            'error': str(e)
        }), 500

if __name__ == '__main__':
    print("🚀 Démarrage de l'API de recommandation Agharas...")
    print("📡 API disponible sur: http://127.0.0.1:5000")
    print("🔗 Endpoints:")
    print("   GET /health")
    print("   GET /recommend?user_id=1")
    app.run(debug=True, host='127.0.0.1', port=5000)