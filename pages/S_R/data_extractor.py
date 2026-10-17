import pandas as pd
import mysql.connector
from sqlalchemy import create_engine
import json

class AgharasDataExtractor:
    def __init__(self, db_config):
        self.db_config = db_config
        self.engine = create_engine(
            f"mysql+mysqlconnector://{db_config['user']}:{db_config['password']}@{db_config['host']}/{db_config['database']}"
        )
    
    def extract_user_ride_interactions(self):
        """Extract user-ride interactions for collaborative filtering"""
        query = """
        SELECT 
            u.id as user_id,
            t.id as ride_id,
            1 as interaction_strength,  # Booking = strong positive signal
            t.depart,
            t.arrive,
            t.prix as price,
            DAYOFWEEK(t.date_trajet) as day_of_week,
            HOUR(t.heure_depart) as hour_of_day,
            (SELECT COUNT(*) FROM reservations r2 WHERE r2.email = u.email) as user_activity_level,
            COALESCE(dr.rating, 0) as rating
        FROM reservations r
        JOIN users u ON r.email = u.email
        JOIN trajet t ON r.trajet_id = t.id
        LEFT JOIN driver_ratings dr ON dr.reservation_id = r.id AND dr.passenger_id = u.id
        WHERE t.date_trajet > CURDATE() - INTERVAL 6 MONTH  # Last 6 months of data
        """
        
        df = pd.read_sql(query, self.engine)
        return df
    
    def extract_available_rides(self):
        """Extract currently available rides for recommendations"""
        query = """
        SELECT 
            id as ride_id,
            depart,
            arrive,
            prix as price,
            date_trajet,
            heure_depart,
            DAYOFWEEK(date_trajet) as day_of_week,
            HOUR(heure_depart) as hour_of_day,
            places_disponibles as available_seats,
            conducteur_id as driver_id
        FROM trajet 
        WHERE status = 'active' 
        AND date_trajet >= CURDATE()
        AND places_disponibles > 0
        """
        
        df = pd.read_sql(query, self.engine)
        return df
    
    def extract_user_profiles(self):
        """Extract user profiles and preferences"""
        query = """
        SELECT 
            u.id as user_id,
            u.nom,
            u.prenom,
            u.email,
            (SELECT COUNT(*) FROM reservations r WHERE r.email = u.email) as total_bookings,
            (SELECT GROUP_CONCAT(DISTINCT t.depart) 
             FROM trajet t 
             JOIN reservations r ON t.id = r.trajet_id 
             WHERE r.email = u.email) as frequently_departed_from,
            (SELECT GROUP_CONCAT(DISTINCT t.arrive) 
             FROM trajet t 
             JOIN reservations r ON t.id = r.trajet_id 
             WHERE r.email = u.email) as frequently_traveled_to,
            (SELECT AVG(prix) 
             FROM trajet t 
             JOIN reservations r ON t.id = r.trajet_id 
             WHERE r.email = u.email) as avg_price_paid
        FROM users u
        WHERE u.is_deleted = 0
        """
        
        df = pd.read_sql(query, self.engine)
        return df
    
    def save_data_for_training(self, filepath='training_data/'):
        """Extract and save all data for model training"""
        import os
        os.makedirs(filepath, exist_ok=True)
        
        print("🔍 Extracting user-ride interactions...")
        interactions_df = self.extract_user_ride_interactions()
        interactions_df.to_csv(f'{filepath}/user_ride_interactions.csv', index=False)
        print(f"✅ Saved {len(interactions_df)} interactions")
        
        print("🔍 Extracting available rides...")
        rides_df = self.extract_available_rides()
        rides_df.to_csv(f'{filepath}/available_rides.csv', index=False)
        print(f"✅ Saved {len(rides_df)} available rides")
        
        print("🔍 Extracting user profiles...")
        users_df = self.extract_user_profiles()
        users_df.to_csv(f'{filepath}/user_profiles.csv', index=False)
        print(f"✅ Saved {len(users_df)} user profiles")
        
        # Create a summary
       # Dans la fonction save_data_for_training, remplace la partie "Create a summary":
# Create a summary
        summary = {
    "total_users": int(len(users_df)),  
    "total_rides": int(len(rides_df)), 
    "total_interactions": int(len(interactions_df)),  
    "unique_user_ride_pairs": int(len(interactions_df[['user_id', 'ride_id']].drop_duplicates())),  # Convert to Python int
    "users_with_ratings": int(interactions_df['rating'].gt(0).sum())  # Convert to Python int
      }

        with open(f'{filepath}/data_summary.json', 'w') as f:
         json.dump(summary, f, indent=2)

        print("📊 Data Summary:")
        for key, value in summary.items():
         print(f"   {key}: {value}")

        return summary
# Usage
if __name__ == "__main__":
    db_config = {
        'host': 'localhost',
        'user': 'root',
        'password': '', 
        'database': 'agharas_bd'
    }
    
    extractor = AgharasDataExtractor(db_config)
    summary = extractor.save_data_for_training() 