<div style="display: flex; align-items: center;">
    <div style="display: flex;">
        <a href="emain.php" class="button" style="margin-right: 16px;">Employee Home</a>
        <a href="logout.php" class="button" style="margin-right: 16px;">Sign Out</a>
        <a href="signupemp.php" class="button" style="margin-right: 16px;">Sign Up Employee Account</a>
        <div class="dropdown" style="position: relative;">
            <button class="dropbtn" style="margin-right: 16px;">Actions</button>
            <div class="dropdown-content" style="right: 0;">
                <a href="add_item.php">Add New Item</a>
                <a href="add_subject.php">Add New Subject</a>
                <a href="add_library.php">Add New Library</a>
                <a href="add_membertype.php">Add New Member Type</a>
                <a href="add_itemtype.php">Add New Item Type</a>
                <a href="deletemember.php">Delete Member</a>
                <a href="order.php">Update Order</a>
            </div>
        </div>
        <div class="dropdown" style="position: relative;">
            <button class="dropbtn" style="margin-right: 16px;">Report</button>
            <div class="dropdown-content" style="right: 0;">
                <a href="report_order.php">View Order Reports</a>
                <a href="report_reservation.php">View Reservation Reports</a>
                <a href="report_member.php">View Member Reports</a>
                <a href="report_employee.php">View Employee Reports</a>
                <a href="report_item.php">View Item Reports</a>
                <a href="report.php">View General Reports</a>
            </div>
        </div>
    </div>

    <style>
        /* Style the dropdown button */
        .dropbtn {
            background-color: #0d47a1;
            color: white;
            padding: 16px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        /* Style the dropdown content (hidden by default) */
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f1f1f1;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        /* Style links inside the dropdown */
        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        /* Change color of dropdown links on hover */
        .dropdown-content a:hover {
            background-color: #0d47a1;
            color: white;
        }

        /* Show the dropdown menu on hover */
        .dropdown:hover .dropdown-content {
            display: block;
        }

        /* Change the background color of the dropdown button when the dropdown content is shown */
        .dropdown:hover .dropbtn {
            background-color: #0d47a1;
        }

        /* Change the background color of the dropdown button when the dropdown button is hovered */
        .dropdown .dropbtn:hover {
            background-color: #0d47a1;
        }
    </style>
</div>