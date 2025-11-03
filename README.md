###  Documentation

---

#### 1a. How to Run the Application Locally with Docker

Follow these steps to run the application on your local machine:

**Prerequisites**
- Ensure you have the following installed:
  - Docker
  - Docker Compose
  - Git
  - A code editor (e.g., VS Code)

**Steps**


1. **Clone the Repository**: Clone the application repository from GitHub:
   ```bash
   git clone https://github.com/francmalo/ecommerce-fullstack-test
   cd ecommerce-fullstack-test
   ```

2. **Set Up Environment Variables**: Create a `.env` file in the root directory and populate it with the necessary environment variables. Use the provided `.env.example` as a template:
   ```bash
   cp .env.example .env
   ```
   Update the variables in `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=db
   DB_PORT=3306
   DB_DATABASE=ecommerce
   DB_USERNAME=root
   DB_PASSWORD=yourpassword

   REDIS_HOST=redis
   REDIS_PORT=6379
   ```

3. **Start the Application with Docker**: Use Docker Compose to build and start the application:
   ```bash
   docker-compose up -d
   ```
   This will:
   - Start the Laravel application.
   - Set up MySQL and Redis containers.

4. **Run Database Migrations**: Access the Laravel container and run the migrations to set up the database schema:
   ```bash
   docker-compose exec app php artisan migrate
   ```

5. **Access the Application**:
   - Open a browser and navigate to [http://localhost](http://localhost) to view the frontend.

6. **Run Tests (Optional)**: To ensure all features are functioning as expected, run the automated tests:
   ```bash
   docker-compose exec app php artisan test
   ```

---

#### 1b. How to Run the Application Locally Without Docker

**Prerequisites**

Ensure the following are installed on your machine:

1. PHP (≥ 8.0)
2. Composer (latest version)
3. MySQL or PostgreSQL
4. Redis
5. A web server like Apache or Nginx

**Steps**

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/francmalo/ecommerce-fullstack-test
   cd ecommerce-fullstack-test
   ```

2. **Set Up Environment Variables**:
   1. Create a `.env` file in the project root by copying the provided `.env.example` file:
      ```bash
      cp .env.example .env
      ```
   2. Update the `.env` file with the correct database and Redis settings:
      ```env
      DB_CONNECTION=mysql
      DB_HOST=127.0.0.1
      DB_PORT=3306
      DB_DATABASE=ecommerce
      DB_USERNAME=root
      DB_PASSWORD=yourpassword

      REDIS_HOST=127.0.0.1
      REDIS_PORT=6379
      ```

3. **Install PHP Dependencies**: Use Composer to install the required PHP dependencies:
   ```bash
   composer install
   ```

4. **Set Up a Local Database**:
   1. Create a new database named `ecommerce` (or the name specified in your `.env` file):
      - For MySQL:
        ```sql
        CREATE DATABASE ecommerce;
        ```
   2. Apply the database migrations:
      ```bash
      php artisan migrate
      ```

5. **Set Up Redis**:
   Ensure Redis is running on your system:
   - Start the Redis server:
     ```bash
     redis-server
     ```
   - Verify Redis is running:
     ```bash
     redis-cli ping
     ```
     If Redis is running, it will respond with `PONG`.

6. **Generate the Application Key**:
   Run the following command to generate an encryption key for the application:
   ```bash
   php artisan key:generate
   ```

7. **Serve the Application**:
   Start the Laravel development server:
   ```bash
   php artisan serve
   ```
   By default, the application will be available at:
   [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

#### 1c. Steps to Deploy the Application with CI/CD Scripts

**Prerequisites**

- A DigitalOcean droplet with Docker and Docker Compose installed.
- SSH access configured for the deployment server.
- A GitHub repository with CI/CD scripts.

**Steps**

1. **Push Code to GitHub**: Ensure your application code is committed and pushed to your GitHub repository:
   ```bash
   git add .
   git commit -m "Deployable application with CI/CD"
   git push origin main
   ```

2. **Set Up GitHub Actions Workflow**: In your repository, create a workflow file at `.github/workflows/deploy.yml`:
   ```yaml
   name: Deploy to DigitalOcean

   on:
     push:
       branches:
         - main

   jobs:
     deploy:
       runs-on: ubuntu-latest

       steps:
         - name: Deploy to DigitalOcean
           uses: appleboy/ssh-action@v0.1.8
           with:
             host: <your-droplet-ip>
             username: root
             key: ${{ secrets.SSH_PRIVATE_KEY }}
             script: |
               cd /path/to/application
               git pull origin main
               docker-compose down
               docker-compose up -d
   ```
   Replace `<your-droplet-ip>` with your droplet’s IP address.

3. **Add Secrets to GitHub**:
   - Navigate to **Settings > Secrets and Variables > Actions** in your GitHub repository.
   - Add the private SSH key from your local machine as `SSH_PRIVATE_KEY`.

4. **Deploy the Application**:
   - Push changes to the `main` branch to trigger the workflow.
   - Monitor the deployment process in the **Actions** tab on GitHub.

5. **Access the Application**:
   - Once deployed, access the application at your droplet’s IP address or domain.

---

The GitHub repository containing the complete project can be accessed here: [https://github.com/francmalo/ecommerce-fullstack-test](https://github.com/francmalo/ecommerce-fullstack-test)

