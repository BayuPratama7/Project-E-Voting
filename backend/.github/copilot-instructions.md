# Copilot Instructions for Laravel E-Voting HIMSI Project

<!-- Use this file to provide workspace-specific custom instructions to Copilot. For more details, visit https://code.visualstudio.com/docs/copilot/copilot-customization#_use-a-githubcopilotinstructionsmd-file -->

## Project Overview
This is a Laravel API project for an e-voting system for HIMSI (Himpunan Mahasiswa Sistem Informasi). The project includes:

- **Authentication**: Admin and Pemilih (Voter) authentication using Laravel Sanctum
- **User Management**: Admin can manage voters (pemilih) and candidates (kandidat)
- **Voting System**: Voters can cast votes for candidates
- **Results**: Real-time voting results and statistics

## Code Standards
1. Follow Laravel coding standards and conventions
2. Use proper MVC architecture
3. Implement API versioning (v1)
4. Use resource controllers for REST API endpoints
5. Implement proper validation using Form Requests
6. Use Eloquent relationships and Query Builder efficiently
7. Follow RESTful API design principles

## Database Schema
- **Users**: Admin users with role-based permissions
- **Pemilih**: Voters with authentication credentials
- **Kandidat**: Candidates with profile information
- **Votes**: Voting records with voter and candidate relationships

## API Structure
```
/api/v1/
├── auth/
│   ├── login
│   ├── logout
│   └── check
├── admin/
│   ├── dashboard
│   └── statistics
├── pemilih/
│   ├── index, create, show, update, delete
│   └── search
├── kandidat/
│   ├── index, create, show, update, delete
│   └── profile management
└── voting/
    ├── cast
    ├── results
    └── status
```

## Security Requirements
- Use Laravel Sanctum for API authentication
- Implement proper authorization policies
- Validate all input data
- Prevent duplicate voting
- Secure file uploads for candidate photos

## Testing
- Write Feature tests for all API endpoints
- Use Laravel's built-in testing tools
- Test authentication and authorization

## Additional Notes
- This project replaces a CodeIgniter REST API system
- Maintain compatibility with existing frontend client
- Use proper HTTP status codes for API responses
- Implement comprehensive error handling
