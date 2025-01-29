# Project Setup and Usage

This project uses **Gitpod** as its Cloud Development Environment (CDE). [gitpod.io](https://gitpod.io).

If you have the Gitpod Chrome extension installed, you can easily open this project and start tinkering with it yourself.

## Purpose of the Project

The purpose of this project is to interact with an external API managed by **QLS**. It uses **FPDI** to amend shipment labels created by QLS, adding order details directly within the same PDF.

At some points, this project became quite a passion project for me. I wanted to create something that was visually engaging, something that could show the process in a tangible way. While I enjoyed working on it, there are definitely areas I would improve upon if I had more time.

## Reasoning for My Approach

I chose the **Laravel** framework for this project because it was something I used to work with daily, but hadn’t touched in about two years. I was curious to see how challenging it would be to get back into it. While I did encounter a few bumps—especially with keeping up with newer developments—I’m glad I invested the time into re-learning and working with it again.

As for the frontend, I decided to use **Vue.js**. This was a bit of a happy coincidence, as I hadn't worked with Vue much before, but I believed it would give me the flexibility I needed to bring my vision to life. My initial thought was to build upon a standard Laravel application foundation, and in the end, it turned out just as I envisioned—aside from the areas I mention later that I would improve with more time.

I also chose to manipulate the existing PDF file, the section for things I would do differently explains some parts as to why. It mainly comes down to me believing it's more expandable and cleaner that way (given some time to refactor).

For creating the README and pull request descriptions, I took advantage of AI to save time. After all, who has the time to manually write markdown files these days? 😄

## Time spent

As per the 29th of January, I have spent approximately 7 hours on front-end. Mainly due to me wanting to give a visual representation of my work.

And about 5 hours on the backend, backend truly is nicer to work with than the frontend ;)

And about 30 minutes in documentation, IE this readme and some simple code comments for the FPDI monolith.

## Things I Would Do Differently

1. **Refactor Monolithic PDF Processing Method:**
   - Currently, the PDF processing method is a bit of a monolith. I would dismantle this method and create a new `FPDI Helper`. This helper would allow me to abstract specific functionalities, such as the creation of a table and predefining 4 possible locations for that table, based on the different label offsets provided by QLS. This would make the code easier to maintain and extend.

2. **Unit Testing:**
   - I would invest more time into unit testing, particularly for creating orders, shipments, and generating PDFs. This would help ensure robustness and reliability.

3. **Refactor and Improve Code Abstraction:**
   - While the code works, I believe it can be further improved in terms of abstraction and cleanliness. Specifically, I’d focus on refactoring the frontend to make it more readable and modular.

4. **Reshape the User Structure:**
   - Currently, the company ID and brand ID are hardcoded. I’m using a basic user structure, but if I had more time, I would transform this into a true QLS customer portal where each company can have multiple users with different roles. The users would be tied to the company via the company’s ID.

5. **API Endpoint for Receiving Orders:**
   - I would spend more time creating a proper API endpoint for receiving orders, as the current solution is hardcoded according to the assessment.

6. **Database Structure:**
   - I am satisfied with the database structure. I believe it avoids redundancy and doesn’t overpopulate any single table. However, future iterations could focus on optimizing the design further.

7. **Cleaner Repository:**
   - I would work on creating a cleaner repository, with more comprehensive code comments and better documentation in the README.

8. **Redis for Queue Management:**
   - I would use Redis for handling queues instead of relying on the database. This, in conjunction with the queue daemon, would make the system more efficient.

9. **Database Seeder:**
   - I would expand the database seeder to include more realistic data, especially related to the idea of a company portal with employees, multiple users, and different access rights.

10. **Iframe Shipment Tracking Progress:**
    - I would consider iframing the shipment tracking progress, so users can easily view updates.

11. **Email Updates for Customers:**
    - I would add checkboxes to the frontend for sending email updates to customers. For instance, when creating a shipment, users could check a box to send the customer an email with the tracking URL.

12. **Database:**
    - I would use a hosted Database, in other words, The current CDE setup is easy and fast. I would invest time in setting up the different services like Redis and Postgres

13. **Automatic shipment creation:**
    - I would automatically dispatch shipment creations to the job queue when a new order is received (if a priviliged user within the company, turns this on in the company settings). See 5.

## Notes on Every New Bootup

Follow these steps to get the project up and running:

1. **Install Mailcatcher (if you want to catch emails):**
   - Run the following command to install Mailcatcher:
     ```bash
     gem install mailcatcher
     ```
   - Then, run Mailcatcher:
     ```bash
     mailcatcher
     ```

2. **Compile the Vue.js Pages:**
   - To compile the Vue.js pages, run:
     ```bash
     npm run dev
     ```

3. **Start the Database Job Daemon:**
   - To start the database job daemon, run:
     ```bash
     php artisan queue:work --daemon
     ```

4. **Seed the Database (optional):**
   - If you want to start with a fresh database and populate it with a sample order, run:
     ```bash
     php artisan db:seed
     ```

## Database

<img width="381" alt="Screenshot 2025-01-29 at 22 51 33" src="https://github.com/user-attachments/assets/0e60ae5c-0f23-4108-8f76-79c8833524fa" />

