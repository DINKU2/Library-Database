University of Houston Library Management System

This project is a web-based library management system for the University of Houston Library. It was developed by Team 5 as part of the COSC 3380 course. The system allows librarians to manage library items, patrons, reservations, and orders, as well as generate reports on library usage.

Team Members

    Huu Loc Trinh

The following technologies were used to develop this project:

    HTML/CSS/JavaScript
    PHP
    MySQL

How to Run the Project

    Clone the repository to your local machine.
    Create an Azure Web App and deploy the project files to it.
    Create an Azure SQL flexible server and import all the dump files in the "dump" folder into your database.
    Update the database connection details in the database.php file to match your Azure SQL server credentials.
    Access the web app URL to use the library management system.

To create an Azure Web App and deploy the project files to it, you can follow these steps:

    Log in to the Azure portal (https://portal.azure.com/).
    Click on "Create a resource" button in the left menu, then select "Web App" under "Web + Mobile".
    Fill in the required information, such as the subscription, resource group, name of the web app, and operating system.
    Under "Publish", select "Code" and then choose your preferred deployment method. You can use FTP/S, GitHub, or Azure DevOps.
    If you choose FTP/S, you will need to configure the FTP/S settings for the web app in the Azure portal.
    Once the web app is created, you can deploy your project files by uploading them through the deployment method you chose.
    After the deployment is complete, you can access the web app through its URL in the Azure portal

    For further information: https://learn.microsoft.com/en-us/azure/app-service/quickstart-php?tabs=cli&pivots=platform-windows

    Note: You will also need to create an Azure SQL Database and update the database connection details in the project files to connect to the database. You can follow the instructions on the Azure portal to create the SQL Database and obtain the connection details.

To create an Azure SQL flexible server and import all the dump files in the "dump" folder into your database, follow these steps:

    Log in to the Azure portal (https://portal.azure.com).
    Click on "Create a resource" in the upper left-hand corner of the screen.
    Search for "Azure SQL" and select "Azure SQL flexible server" from the results.
    Click "Create" to begin creating the server.
    Fill out the required information, such as server name, admin username and password, and location.
    Choose the appropriate pricing tier and click "Review + create" to review your selections.
    Click "Create" to create the server.
    Once the server is created, click on the "Firewalls and virtual networks" tab and add your IP address to the firewall rules.
    Next, click on "Query editor (preview)" in the left-hand menu.
    Connect to your database using the server name, admin username and password, and the database name you created earlier.
    Upload the dump files from the "dump" folder using the "Import Data-tier Application" feature in the query editor.
    Once the import is complete, you can connect to the database using the database connection details in the "database.php" file of your project.

    For further information: https://learn.microsoft.com/en-us/azure/mysql/flexible-server/quickstart-create-server-portal

    Note: Make sure to update the database connection details in the "database.php" file to match your Azure SQL flexible server.
Features

    Employee - This role allows employees to perform their work duties. The employee view has the following functionality
    Signing up new employees
    Sign-in/Sign-out
    Handling checkouts
    Returning checkouts
    Add new items
    Add new Library
    Add new item copy
    Add new subject
    Add new member type
    Adding new item type
    Delete member
    Edit member information
    Edit employee information
    Edit item information
    Search items
    View detailed Item information
    
    Member - This role allows library members to take advantage of the libraries’ services. The member view has the following functionality:
    Member sign-up
    Sign-in/Sign-out
    Change member information
    Search Items
    View item details
    Reserve Items
    View current/past reservations
    Add item to cart
    Check cart
    Check out items
    View current/past orders
    View messages
    Delete messages
    Check late fee
    Pay late fee

