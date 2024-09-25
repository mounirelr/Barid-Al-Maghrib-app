# Application Redesign

## Project Overview
This project addresses the oscillation problem between the "colis" and "courrier" applications by streamlining the shipment process through the integration of both services into a single application. The goal is to facilitate price calculation for each service, reduce shipment processing time, and enhance the overall efficiency of agents in agencies.

## Key Features

### 1. Unified Shipment Application
- The application is designed to handle both "colis" and "courrier" services on a single platform.
- It eliminates the need to switch between different applications for calculating shipment prices.
- Shipment prices (for both colis and courrier) are determined based on the entered weight.

### 2. Optimized Shipment Processing
- Agents can enter shipment information (weight, customer, and recipient details) just once, regardless of the shipment type (colis or courrier).
- **Customer Data Lookup**: The application checks for existing customer records in the database. If a customer already exists, their information is automatically retrieved, minimizing repetitive data entry and significantly reducing processing time.

### 3. Performance Overview and Reporting
- A dedicated **Overview Page** displays a summary of daily performance metrics (e.g., total shipments, revenue ...etc).
- A **Report Page** includes filters (such as date range and agency selection) and features data visualization through interactive graphs, providing deeper insights for informed decision-making.
- This functionality helps supervisors and decision-makers monitor operations and evaluate the performance of different agencies over various time periods.

## Objectives
- **Reduce Shipment Processing Time**: Entering shipment information once for both "colis" and "courrier" services decreases the workload on counter agents, speeds up the customer experience, and reduces the time clients spend waiting in line.
- **Enhance Decision-Making**: The performance overview and reporting pages offer actionable data to support informed decision-making.
- 
## Tech Stack 
This application is developed using **HTML, CSS, PHP, JavaScript, AJAX**, and **MySQL** for the database.

## Contributing
If you would like to contribute to the project, feel free to open a pull request or submit an issue. Contributions such as bug reports, feature requests, or code improvements are welcome!
