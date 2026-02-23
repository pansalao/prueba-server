import psycopg2
from psycopg2 import Error

def list_tables():
    config = {
        'host': 'uptp.sytes.net',
        'user': 'info',
        'password': 'info2025##',
        'database': 'bd_daece',
        'port': 5432
    }

    try:
        # Establecer la conexión
        connection = psycopg2.connect(**config)
        cursor = connection.cursor()
        
        # Consultar los nombres de las tablas en el esquema 'public'
        query = """
            SELECT table_name 
            FROM information_schema.tables 
            WHERE table_schema = 'public'
            AND table_type = 'BASE TABLE'
            ORDER BY table_name;
        """
        cursor.execute(query)
        tables = cursor.fetchall()

        if not tables:
            print("No se encontraron tablas en la base de datos.")
        else:
            print("Tablas encontradas:")
            for table in tables:
                print(f"- {table[0]}")

    except Error as e:
        print(f"Error al conectar o consultar la base de datos: {e}")
    
    finally:
        if 'connection' in locals() and connection:
            cursor.close()
            connection.close()

if __name__ == "__main__":
    list_tables()