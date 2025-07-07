# Uber Minimum Wage Calculator

A Laravel-based web application that calculates minimum wage for Uber drivers across Canadian provinces, with special support for Ontario's Digital Platform Workers' Rights Act.

## 🚗 Features

- **Province-based minimum wage calculation** for all Canadian provinces/territories
- **Ontario Digital Platform Workers' Rights Act** support ($17.20/hour minimum)
- **IP-based province detection** for automatic location selection
- **Time format support** (Hours:Minutes - e.g., 8:30)
- **Net fare and tips integration** for complete earnings calculation
- **Uber API integration** for automatic data import (optional)
- **Responsive Uber-style UI** with clean, professional design

## 📊 Calculation Formula

1. **Minimum Wage = Active Time × Hourly Rate**
2. **Hour Total = Minimum Wage - Net Fare** (amount owed to driver)
3. **Total Earnings = Hour Total + Net Fare + Tips**

The "Hour Total" represents the minimum wage top-up that platforms must pay drivers under the Digital Platform Workers' Rights Act.

## 🛠 Installation

### Prerequisites
- PHP 8.1+
- Composer
- MySQL/SQLite
- Node.js & NPM (for assets)

### Setup Steps

1. **Clone the repository**
```bash
git clone <repository-url>
cd uber-minimum-wage-calculator
```

2. **Install dependencies**
```bash
composer install
npm install
```

3. **Environment configuration**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Database setup**
```bash
# For MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=uber_wage_calculator
DB_USERNAME=root
DB_PASSWORD=your_password

# Or for SQLite (simpler)
DB_CONNECTION=sqlite
```

5. **Run migrations and seeders**
```bash
php artisan migrate
php artisan db:seed
```

6. **Start the development server**
```bash
php artisan serve
```

Visit `http://localhost:8000` to use the calculator.

## 🔌 Uber API Integration (Optional)

### Setting up Uber Developer Account

⚠️ **Important:** The current Uber API credentials in the code are invalid/demo credentials. To enable real Uber integration, you need to:

1. **Register as Uber Developer**
   - Go to [Uber Developer Portal](https://developer.uber.com/)
   - Create an account and verify your identity
   - Apply for API access (may require business verification)

2. **Create an Application**
   - Create a new app in the developer dashboard
   - Set your redirect URI to: `http://your-domain.com/uber/callback`
   - Note down your Client ID and Client Secret

3. **Configure Environment Variables**
```bash
# Add to .env file
UBER_CLIENT_ID=your_actual_client_id
UBER_CLIENT_SECRET=your_actual_client_secret
```

4. **Request Appropriate Scopes**
   - `partner.trips` - For trip data access
   - `partner.payments` - For earnings data access
   - May require special approval from Uber

### API Limitations

- **Access Restrictions:** Uber limits API access to verified partners
- **Scope Requirements:** Trip and payment data requires special permissions
- **Rate Limits:** API calls are limited per application
- **Data Privacy:** Subject to Uber's data usage policies

### Alternative Solutions

If official API access isn't available:

1. **Manual CSV Import** (recommended)
   - Drivers export earnings from Uber Driver app
   - Upload CSV files to the calculator
   - Automatic parsing and calculation

2. **Manual Data Entry** (current default)
   - Drivers enter active time, net fare, and tips manually
   - Still provides accurate minimum wage calculations

## 🗺 Province Data

The application includes current minimum wage rates for all Canadian provinces:

- **Ontario:** $17.20/hr (Digital Platform Workers' Rights Act)
- **British Columbia:** $17.85/hr
- **Nunavut:** $19.00/hr (highest in Canada)
- **Alberta:** $15.00/hr
- And all other provinces/territories...

Data is regularly updated based on [Government of Canada minimum wage rates](https://minwage-salairemin.service.canada.ca/en/general.html).

## 🎨 Design

The application features an authentic Uber-style design:
- **Black header** with Uber branding
- **Clean white cards** on dark background
- **Professional typography** with UberMove font family
- **Responsive design** for mobile and desktop

## 🧪 Testing

```bash
# Run tests
php artisan test

# Run specific test suites
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit
```

## 📝 API Endpoints

### Public Endpoints
- `GET /` - Main calculator interface
- `POST /calculate` - Calculate wages
- `GET /api/provinces` - Get province wage rates

### Uber Integration Endpoints
- `GET /uber/authorize` - Start Uber OAuth flow
- `GET /uber/callback` - OAuth callback handler
- `GET /uber/fetch-data` - Fetch driver trip/earnings data
- `POST /uber/disconnect` - Disconnect from Uber

## 🔒 Security

- **CSRF Protection** on all forms
- **Input validation** for all user data
- **Secure OAuth flow** for Uber integration
- **Session-based token storage** (recommend database storage for production)

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Submit a pull request

## ⚠️ Disclaimer

This calculator is for informational purposes only. Always verify calculations with official government sources and consult legal professionals for employment law questions.

**Government Reference:** [Official Canadian Minimum Wage Rates](https://minwage-salairemin.service.canada.ca/en/general.html)