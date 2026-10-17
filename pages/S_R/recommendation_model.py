import pandas as pd
import numpy as np
from sklearn.metrics.pairwise import cosine_similarity
from sklearn.preprocessing import StandardScaler
from sklearn.feature_extraction.text import CountVectorizer
import json
from datetime import datetime

class AgharasRecommendationEngine:
    def __init__(self, data_path='training_data/'):
        self.data_path = data_path
        self.user_similarity_matrix = None
        self.ride_features = None
        self.user_profiles = None
        
    def load_data(self):
        """Charge les données préparées"""
        print("📂 Chargement des données...")
        self.interactions = pd.read_csv(f'{self.data_path}/user_ride_interactions.csv')
        self.rides = pd.read_csv(f'{self.data_path}/available_rides.csv')
        self.users = pd.read_csv(f'{self.data_path}/user_profiles.csv')
        
        print(f"✅ Données chargées: {len(self.interactions)} interactions, {len(self.rides)} trajets, {len(self.users)} utilisateurs")
    
    def prepare_collaborative_filtering(self):
        """Prépare la matrice utilisateur-trajet pour le filtrage collaboratif"""
        print("🔧 Préparation du filtrage collaboratif...")
        
        # Créer la matrice utilisateur-trajet
        user_ride_matrix = self.interactions.pivot_table(
            index='user_id',
            columns='ride_id', 
            values='interaction_strength',
            fill_value=0
        )
        
        # Calculer la similarité entre utilisateurs
        self.user_similarity_matrix = cosine_similarity(user_ride_matrix)
        self.user_similarity_df = pd.DataFrame(
            self.user_similarity_matrix,
            index=user_ride_matrix.index,
            columns=user_ride_matrix.index
        )
        
        print(f"✅ Matrice de similarité créée: {self.user_similarity_matrix.shape}")
    
    def prepare_content_features(self):
        """Prépare les caractéristiques des trajets pour le filtrage basé sur le contenu"""
        print("🔧 Préparation des caractéristiques des trajets...")
        
        # Préparer les features des trajets
        ride_features = self.rides.copy()
        
        # Encoder les villes de départ et d'arrivée
        cities = ride_features['depart'] + " " + ride_features['arrive']
        vectorizer = CountVectorizer()
        city_vectors = vectorizer.fit_transform(cities)
        
        # Features numériques
        numerical_features = ride_features[['price', 'day_of_week', 'hour_of_day', 'available_seats']]
        
        # Normaliser les features numériques
        scaler = StandardScaler()
        numerical_scaled = scaler.fit_transform(numerical_features)
        
        # Combiner toutes les features
        self.ride_features = pd.DataFrame(
            np.hstack([numerical_scaled, city_vectors.toarray()]),
            index=ride_features['ride_id']
        )
        
        print(f"✅ Caractéristiques des trajets préparées: {self.ride_features.shape}")
    
    def get_user_preferences(self, user_id):
        """Extrait les préférences d'un utilisateur basées sur son historique"""
        user_interactions = self.interactions[self.interactions['user_id'] == user_id]
        
        if len(user_interactions) == 0:
            return None
            
        preferences = {
            'preferred_departures': user_interactions['depart'].value_counts().head(3).index.tolist(),
            'preferred_destinations': user_interactions['arrive'].value_counts().head(3).index.tolist(),
            'preferred_price_range': (user_interactions['price'].min(), user_interactions['price'].max()),
            'preferred_days': user_interactions['day_of_week'].value_counts().head(3).index.tolist(),
            'preferred_hours': user_interactions['hour_of_day'].value_counts().head(3).index.tolist()
        }
        
        return preferences
    
    def recommend_for_user(self, user_id, top_n=5):
        """Génère des recommandations pour un utilisateur"""
        print(f"🎯 Génération de recommandations pour l'utilisateur {user_id}...")
        
        # 1. Filtrage collaboratif: trouver des utilisateurs similaires
        if user_id in self.user_similarity_df.index:
            similar_users = self.user_similarity_df[user_id].sort_values(ascending=False)[1:6]  # Top 5 similaires
            
            # Trajets que les utilisateurs similaires ont pris
            similar_users_interactions = self.interactions[
                self.interactions['user_id'].isin(similar_users.index)
            ]
            
            # Trajets que l'utilisateur actuel n'a pas encore pris
            user_taken_rides = self.interactions[
                self.interactions['user_id'] == user_id
            ]['ride_id'].tolist()
            
            recommended_rides = similar_users_interactions[
                ~similar_users_interactions['ride_id'].isin(user_taken_rides)
            ]
            
            if len(recommended_rides) > 0:
                collaborative_scores = recommended_rides['ride_id'].value_counts().head(top_n)
                collaborative_recommendations = collaborative_scores.index.tolist()
            else:
                collaborative_recommendations = []
        else:
            collaborative_recommendations = []
        
        # 2. Filtrage basé sur le contenu: trajets similaires aux préférences utilisateur
        user_preferences = self.get_user_preferences(user_id)
        
        if user_preferences and len(self.rides) > 0:
            # Filtrer par préférences
            content_recommendations = self.rides.copy()
            
            # Appliquer les filtres basés sur les préférences
            if user_preferences['preferred_departures']:
                content_recommendations = content_recommendations[
                    content_recommendations['depart'].isin(user_preferences['preferred_departures'])
                ]
            
            if user_preferences['preferred_destinations'] and len(content_recommendations) > 0:
                content_recommendations = content_recommendations[
                    content_recommendations['arrive'].isin(user_preferences['preferred_destinations'])
                ]
            
            # Exclure les trajets déjà pris
            if user_preferences:
                user_taken_rides = self.interactions[
                    self.interactions['user_id'] == user_id
                ]['ride_id'].tolist()
                content_recommendations = content_recommendations[
                    ~content_recommendations['ride_id'].isin(user_taken_rides)
                ]
            
            content_recommendations = content_recommendations['ride_id'].head(top_n).tolist()
        else:
            content_recommendations = []
        
        # 3. Combiner les recommandations
        all_recommendations = list(set(collaborative_recommendations + content_recommendations))
        
        # Si pas assez de recommandations, ajouter des trajets populaires
        if len(all_recommendations) < top_n:
            popular_rides = self.interactions['ride_id'].value_counts().head(top_n).index.tolist()
            additional_rides = [ride for ride in popular_rides if ride not in all_recommendations]
            all_recommendations.extend(additional_rides[:top_n - len(all_recommendations)])
        
        return all_recommendations[:top_n]
    
    def train_model(self):
        """Entraîne le modèle complet"""
        print("🤖 Entraînement du modèle de recommandation...")
        self.load_data()
        self.prepare_collaborative_filtering()
        self.prepare_content_features()
        print("✅ Modèle entraîné avec succès!")
    
    def test_recommendations(self):
        """Teste le modèle sur quelques utilisateurs"""
        print("\n🧪 Test des recommandations...")
        
        # Prendre quelques utilisateurs au hasard
        test_users = self.interactions['user_id'].unique()[:3]
        
        for user_id in test_users:
            recommendations = self.recommend_for_user(user_id)
            user_prefs = self.get_user_preferences(user_id)
            
            print(f"\n--- Utilisateur {user_id} ---")
            print(f"Préférences: {user_prefs}")
            print(f"Recommandations: {recommendations}")
            
            # Afficher les détails des trajets recommandés
            for ride_id in recommendations:
                ride_info = self.rides[self.rides['ride_id'] == ride_id]
                if not ride_info.empty:
                    ride = ride_info.iloc[0]
                    print(f"  🚗 {ride['depart']} → {ride['arrive']} | {ride['price']} DH")

# Utilisation
if __name__ == "__main__":
    # Initialiser et entraîner le modèle
    engine = AgharasRecommendationEngine()
    engine.train_model()
    
    # Tester les recommandations
    engine.test_recommendations()
    
    # Sauvegarder le modèle pour une utilisation future
    print("\n💾 Modèle prêt pour l'intégration avec PHP!")