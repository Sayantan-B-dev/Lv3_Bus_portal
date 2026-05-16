# Ultimate Beginner's Deployment Guide

Welcome! If you are reading this, you are ready to take your project from your local computer (XAMPP) and put it on the live internet so anyone can see it. 

This guide assumes you know **absolutely nothing** about deployment. We will go step-by-step.

---

## PREP WORK: DO THIS BEFORE ANYTHING ELSE

Before putting your site on the internet, you need to prepare your files and your database.

### 1. Export Your Local Database
Your live website cannot read the database on your computer. You must export it.
1. Open your XAMPP Control Panel and start **Apache** and **MySQL**.
2. Go to your browser and type: `http://localhost/phpmyadmin`
3. Click on your database name on the left side.
4. Click the **"Export"** tab at the top.
5. Leave everything as default (Format: SQL) and click the **"Export"** or **"Go"** button.
6. A `.sql` file will download to your computer. Keep this safe!

### 2. Zip Your Project Files
For some platforms, you need to upload a zipped folder of your files.
1. Go to your project folder (`c:\xampp\htdocs\yatrapath`).
2. Select **all the files and folders inside** (do not just zip the `yatrapath` folder itself, zip the contents).
3. Right-click -> "Compress to ZIP file" (or use WinRAR/7Zip). Name it `project.zip`.

---

## OPTION 1: InfinityFree (Highly Recommended for Beginners)
InfinityFree is the best starting point because it gives you **both website hosting AND a database for free** in one place.

### Step 1: Create an Account
1. Go to [infinityfree.com](https://infinityfree.com/) and click "Register".
2. Verify your email address and log in.

### Step 2: Create a Hosting Account
1. In the InfinityFree dashboard, click **"Create Account"**.
2. Choose a free subdomain (e.g., `mybusportal.epizy.com` or `mybusportal.great-site.net`).
3. Click "Check Availability".
4. Enter an account password and click "Create Account".
5. Wait a few minutes, then click **"Control Panel"** (approve the "I Approve" notice if it pops up).

### Step 3: Upload Your Database
1. Inside the Control Panel, scroll down to the **"Databases"** section and click **"MySQL Databases"**.
2. Create a new database (e.g., name it `bus_db`). 
3. Note down the **MySQL User Name**, **MySQL Password**, and **MySQL Host Name** shown on this page. You will need them!
4. Click the **"Admin"** button next to your database to open phpMyAdmin.
5. Click the **"Import"** tab at the top.
6. Choose the `.sql` file you downloaded in the Prep Work and click "Go". Your database is now live!

### Step 4: Upload Your Files
1. Go back to the InfinityFree dashboard for your account and click **"File Manager"**.
2. Open the **`htdocs`** folder. **CRITICAL: Everything must go inside the `htdocs` folder!**
3. Delete the default files inside `htdocs` (like `index2.html`).
4. Upload your `project.zip` file.
5. Right-click the zip file and choose **"Extract"**. 

### Step 5: Connect Your Code to the Live Database
1. Find your database connection file in the File Manager (usually `config/database.php`, `config.php`, or `.env`).
2. Right-click the file and click **"Edit"**.
3. Change your local credentials to your InfinityFree credentials:
   - **Host:** Change `localhost` to the MySQL Host Name (e.g., `sql123.epizy.com`)
   - **User:** Change `root` to your MySQL User Name (e.g., `epiz_123456`)
   - **Password:** Change `""` (blank) to your InfinityFree Account Password.
   - **Database:** Change to your InfinityFree Database Name.
4. Save the file. Your website is now live!

---

## OPTION 2: 000webhost (Important Notice)

**000webhost has been permanently shut down by its parent company, Hostinger, in 2024.** You cannot use it anymore.

**Alternatives to 000webhost:**
- **AwardSpace:** Similar to InfinityFree. Gives you a free subdomain, PHP hosting, and a MySQL database. The steps are almost identical to InfinityFree (Upload to File Manager, Create DB, Edit Config).
- **ByetHost:** Another free alternative that works exactly like InfinityFree.

---

## OPTION 3: Railway.app (Modern & Professional, but Harder)
Railway is a professional deployment platform. It does **not** have a traditional File Manager, and it does not have a "free forever" tier anymore (it offers a $5 trial). It requires you to use **GitHub**.

### Step 1: Put Your Code on GitHub
1. Create an account on [github.com](https://github.com/).
2. Download GitHub Desktop, log in, and click "Create a New Repository on your hard drive".
3. Move your project files into this new folder.
4. Open GitHub Desktop, write a summary (e.g., "Initial commit"), and click **"Commit to main"**.
5. Click **"Publish repository"** to push it to the internet.

### Step 2: Connect to Railway
1. Go to [railway.app](https://railway.app/) and log in with your GitHub account.
2. Click **"New Project"** -> **"Deploy from GitHub repo"**.
3. Select your yatrapath repository.
4. Railway will automatically detect it is a PHP app and start building it.

### Step 3: Add Environment Variables
1. Click on your newly deployed app block in Railway.
2. Go to the **"Variables"** tab.
3. This is where you put your database credentials (since you shouldn't hardcode them on GitHub). You will need an external database (see the next section).

---

## 🗄️ FREE EXTERNAL SQL DATABASES
If you use platforms like Railway, Render, or Vercel, they do **not** give you a free MySQL database. You have to get the database from somewhere else and connect them.

Here is the best free MySQL service and how to use it:

### The Best Choice: Aiven (Free MySQL)
Aiven provides a completely free, professional-grade MySQL database.

**Steps to use Aiven:**
1. Go to [aiven.io](https://aiven.io/) and create a free account.
2. Click **"Create Service"**.
3. Choose **MySQL** as the service type.
4. For the cloud provider, choose any (DigitalOcean, AWS, etc.).
5. For the Service Plan, select the **Free Plan** (usually at the bottom or top depending on the UI).
6. Give it a name and click "Create".
7. Once created, click on your service. You will see a "Connection Information" box showing:
   - `Host` (e.g., mysql-xyz.aivencloud.com)
   - `Port` (e.g., 25060)
   - `User` (e.g., avnadmin)
   - `Password` (a long random string)
   - `Database Name` (defaultdb)

**How to connect it to your website:**
1. You need a database management tool on your computer, like **DBeaver** or **MySQL Workbench** (since XAMPP phpMyAdmin only works for your local PC).
2. Connect DBeaver using the Aiven credentials.
3. Once connected, run your `.sql` export file to create your tables and insert your data.
4. Finally, update your website's PHP connection code to use the Aiven Host, Port, User, and Password. 

*(Note: Since Aiven uses a non-standard port like 25060 instead of 3306, make sure your PHP connection code specifies the port!)*

### Other Free SQL Alternatives:
- **AlwaysData:** Offers 100MB of free hosting and MySQL. Good for small test projects.
- **Supabase / Neon:** These are incredible free databases, but they use **PostgreSQL**, not MySQL. If your code uses standard SQL, you might be able to switch, but if you used specific MySQL commands, stick to Aiven.

---

## FINAL DEPLOYMENT CHECKLIST
No matter which platform you choose, always check these after deploying:
- [ ] Did I import my database tables successfully?
- [ ] Did I update my database connection file (host, user, pass, dbname)?
- [ ] If my code uses `http://localhost/yatrapath` for links or images, did I change them to my new live domain name?
- [ ] Are all my files uploaded in the correct folder (like `htdocs`)?
