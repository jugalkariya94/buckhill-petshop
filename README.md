# Buckhill Petshop Test

Solution for the Buckhill Petshop test.

## Installation

Follow these steps to get your development environment running:

1. **Clone the repository**

   ```bash
   git clone https://github.com/jugalkariya94/buckhill-petshop.git
   ```

2. **Navigate to the project directory**

   ```bash
   cd buckhill-petshop
   ```

3. **Environment Variables**

   Copy the `.env.example` file to a new file named `.env` and update the environment variables according to your local setup.

   ```bash
   cp .env.example .env
   ```
   
4. **Update the following environment variables in the `.env` file with your values:**

   ```bash
    APP_NAME=Buckhill Petshop
    APP_URL=http://localhost:8000
    DB_CONNECTION=mysql
    DB_HOST=host.docker.internal
    DB_PORT=3306
    DB_DATABASE=buckhill_petshop
    DB_USERNAME=root
    DB_PASSWORD=
    VITE_API_URL=http://localhost:8000/api/v1
   
   ```
5. **Build and Run with Docker**

   Use Docker Compose to build and run the application and its associated services.

   ```bash
   docker compose up --build -d
   ```

   This command will start all the services defined in your `compose.yml` file. It might take a few minutes the first time you run this command since Docker needs to download the images and build the containers.
6. **Generate Application Key**

   ```
    docker compose exec buckhill-backend php artisan key:generate
    ```
7. **Run Migrations**

   ```
    docker compose exec buckhill-backend php artisan migrate --seed
    
    ```

## Usage

After successfully running the application using Docker, you can access the application at `http://localhost:8000` or the port you specified in your `.env` file.

## Running Tests

To run tests, execute the following command:

```bash
docker compose exec buckhill-backend php artisan test
```


## Contact

Jugal Kariya - jugalkariya@gmail.com
