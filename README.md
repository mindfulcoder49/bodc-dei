
<img src="https://github.com/mindfulcoder49/bodc-dei/blob/main/public/images/logo.png" alt="BODC-DEI Logo" width="200"/>


# BODC-DEI Repository

Welcome to the  project! This project is an initiative to foster the adoption of open data and open-source solutions within Boston's vibrant AI and ML landscape. The repository contains the code and documentation for our web application, which includes several components and features aimed at empowering communities through technology.

Sponsored by [AlcivarTech](https://alcivartech.com). Visit [BODC-DEI](https://boston-ai-project.alcivartech.com) for more information.

## Features

- **About Page:** Describes the mission and objectives of BODC-DEI.
- **Contact Page:** Provides information on how to connect with the project via GitHub.
- **Crime Map:** A web application that displays crime data in Boston using a map interface.
- **Dashboard:** A comprehensive dashboard with components for crime reporting and GitHub analysis.
- **Projects Page:** Showcases various open-source projects related to AI and ML.
- **The Boston App Demo:** A demonstration of an app that generates personalized location-based reports.
- **311 Case List:** Displays a list of 311 cases with detailed predictions and insights.
- **311 Demo:** A code demo for the 311 project.
- **311 Model Tracker:** Tracks the accuracy of different predictive models used in the 311 project.
- **Welcome Page:** The home page of the BODC-DEI website.

## Technologies Used

- **Laravel:** A PHP framework for building robust web applications.
- **Inertia.js:** A library that allows you to build single-page applications using classic server-side routing and controllers.
- **Vue.js:** A progressive JavaScript framework for building user interfaces.
- **Tailwind CSS:** A utility-first CSS framework for styling the application.

## Installation

To get started with the BODC-DEI project, follow these steps:

1. **Clone the repository:**
   ```bash
   git clone https://github.com/mindfulcoder49/BODC-DEI.git
   cd BODC-DEI
   ```

2. **Install dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Set up environment variables:**
   Copy the `.env.example` file to `.env` and update the necessary environment variables.

4. **Generate application key:**
   ```bash
   php artisan key:generate
   ```

5. **Run database migrations:**
   ```bash
   php artisan migrate
   ```

6. **Build assets:**
   ```bash
   npm run dev
   ```

7. **Serve the application:**
   ```bash
   php artisan serve
   ```

## Project Structure

The project follows a standard Laravel structure with additional Vue.js components. Here are some of the important files and directories:

- **/resources/js/Pages:** Contains the Vue.js components for each page.
  - `About.vue`
  - `Contact.vue`
  - `CrimeMap.vue`
  - `Dashboard.vue`
  - `Projects.vue`
  - `TheBostonAppDemo.vue`
  - `ThreeOneOneCaseList.vue`
  - `ThreeOneOneDemo.vue`
  - `ThreeOneOneModelList.vue`
  - `ThreeOneOneProject.vue`
  - `Welcome.vue`

- **/resources/js/Components:** Contains reusable Vue.js components.
  - `CrimeMapComponent.vue`
  - `PageTemplate.vue`

- **/resources/views:** Contains the Blade templates used by Laravel Breeze.
  - `app.blade.php`
  - `welcome.blade.php`

- **/routes/web.php:** Defines the web routes for the application.

## Contributing

We welcome contributions from the community. If you would like to contribute to the project, please follow these steps:

1. **Fork the repository.**
2. **Create a new branch:**
   ```bash
   git checkout -b my-feature-branch
   ```
3. **Make your changes and commit them:**
   ```bash
   git commit -m "Add new feature"
   ```
4. **Push to the branch:**
   ```bash
   git push origin my-feature-branch
   ```
5. **Submit a pull request.**

## License

This project is open-source and licensed under the MIT License. See the [LICENSE](LICENSE) file for more details.

## Contact

If you have any questions or need further assistance, please open an issue on GitHub or contact us via the Contact page on the website.

Thank you for your interest in the BODC-DEI project! Together, we can make a positive impact through open technology and data.