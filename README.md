# 🎮 Game Chaser

Game Chaser is a dynamic and fully-functional database management system tailored for video game enthusiasts, parents, and administrators. This web-based platform provides an intuitive interface for exploring, managing, and gaining insights into video game data, including reviews, ratings, genres, platforms, and awards.

Developed as a collaborative team project, Game Chaser demonstrates advanced skills in database design, PHP development, and MySQL integration. It showcases the ability to conceptualize, design, and deploy a real-world application from scratch.

---

## 🚀 Key Features

### **🎯 User-Focused Functionality**
- **Search Games**: Filter video games dynamically by genre, platform, or awards.
- **User Roles**: Support for multiple user roles, including:
  - **Gamers**: Discover and review games.
  - **Parents**: Access star ratings, age suitability, and platform compatibility.
  - **Administrators**: Manage database records, generate reports, and oversee user accounts.

### **🔍 Advanced Search & Insights**
- Search and filter games by:
  - **Genre** (e.g., Action, RPG, Adventure).
  - **Platform** (e.g., PC, PlayStation, Nintendo Switch).
  - **Awards** (e.g., Game of the Year).
- Generate dynamic reports and run ad-hoc queries for deeper insights into gaming trends.

### **🛠️ Comprehensive Management Tools**
- **Game Management**:
  - Add new video games with details like platform compatibility, genre, and awards.
  - Edit existing game records for updated data.
  - Remove outdated or incorrect entries.
- **Award Tracking**:
  - Associate awards with specific games.
  - Manage award categories and issuer information.
- **User Account Management**:
  - Create, update, and delete user profiles.
  - Assign user roles dynamically.

---

## 🗂️ Database Design

### **📋 ER Diagram**
Game Chaser’s database design captures the core entities and their relationships:
- **Users**: Tracks login credentials and roles.
- **Video Games**: Stores game details, including star ratings and platforms.
- **Genres**: Categorizes games into well-defined genres.
- **Platforms**: Links games to consoles, PCs, and mobile devices.
- **Awards**: Highlights accolades associated with games.

### **📊 Relational Schema**
- Implements foreign key constraints to maintain referential integrity.
- Optimized for scalability and efficient querying.

---

## 🛠️ Tech Stack
### **Languages & Tools**
- **PHP**: Backend logic and server-side scripting.
- **MySQL**: Database management and queries.
- **Apache**: Local server for hosting the application.
- **HTML, CSS**: Responsive and user-friendly interface design.

### **Features Showcased**
- User authentication and role-based access.
- CRUD operations for games, awards, and genres.
- Dynamic data visualization with interactive reports.

---

## 📖 How to Set Up Game Chaser

### **System Requirements**
- **PHP**: Version 7.4 or higher.
- **MySQL**: Version 5.7 or higher.
- **Apache Server**: Compatible web server.

### **Installation**
1. **Clone the Repository**:
   ```bash
   git clone https://github.com/hernandekr4/Game_Chaser.git
   cd Game_Chaser
