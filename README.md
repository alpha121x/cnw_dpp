# Construction Project Management System

A comprehensive PHP-based system for managing construction projects, designed to streamline the workflow from work order issuance to final payment.

## Features

- Role-based authentication for multiple user types (Admin/SDO, Contractor, Sub Engineer, SDC, XEN, DAO, etc.)
- Work order management
- Measurement book entries
- Bill submission and approval workflow
- Voucher and form generation
- Cheque processing and payment tracking

## Installation

1. Clone the repository to your web server
2. Create a MySQL database named `construction_mgmt`
3. Import the database schema from `db_setup.sql`
4. Update database connection details in `config/database.php`
5. Access the system through your web browser

## Default Login Credentials

- **Admin:** admin@example.com / 123
- **SDO:** sdo@example.com / 123
- **Contractor:** contractor1@example.com / 123
- **Sub Engineer:** subeng@example.com / 123
- **SDC:** sdc@example.com / 123
- **XEN:** xen@example.com / 123
- **DAO:** dao@example.com / 123
- **Accounts:** accounts@example.com / 123
- **Treasury:** treasury@example.com / 123

## Workflow

The system follows a step-by-step workflow:

1. **Work Order Issuance** - Admin/SDO creates and assigns work orders to contractors
2. **Work Commencement** - Contractor confirms the start of work
3. **Measurement Entry** - Sub Engineer records work measurements
4. **Bill Submission** - Contractor submits bills for payment
5. **Multi-level Approval** - Bills go through SDO, SDC, XEN, and DAO for verification
6. **Payment Processing** - Cheque generation, form creation, and final payment

## Requirements

- PHP 7.3 or higher
- MySQL 5.7 or higher
- Web server (Apache, Nginx)

## License

This project is licensed under the MIT License - see the LICENSE file for details.