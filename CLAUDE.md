# CLAUDE.md - OrcaPro Codebase Guide for AI Assistants

This document provides a comprehensive guide to the OrcaPro codebase structure, development workflows, and conventions for AI assistants working on this project.

## Project Overview

**OrcaPro** is a Laravel 12 web application for managing budgets and work orders with client collaboration features. Built with Livewire and Flux UI, it provides a modern, reactive interface with Portuguese (Brazilian) localization.

### Key Technologies
- **Backend**: Laravel 12.0, PHP 8.2+
- **Frontend**: Livewire 3, Flux 2.1.1, Volt 1.7.0
- **Styling**: Tailwind CSS 4.0
- **Authentication**: Laravel Fortify with 2FA
- **Testing**: Pest PHP 4.1
- **Database**: SQLite (dev), supports MySQL/PostgreSQL
- **Build**: Vite 7.0

---

## Directory Structure

### `/app` - Application Code (28 files)

```
app/
├── Actions/Fortify/           # Authentication logic
│   ├── CreateNewUser.php      # User registration
│   ├── ResetUserPassword.php  # Password reset
│   └── PasswordValidationRules.php
│
├── Http/Controllers/          # Traditional controllers (5 files)
│   ├── BudgetController.php   # Budget CRUD operations
│   ├── BudgetPublicController.php # Public budget access (no auth)
│   ├── WorkOrderController.php    # Work order management
│   ├── DashboardController.php
│   └── Controller.php
│
├── Livewire/                  # Livewire components (9 files)
│   ├── BudgetForm.php         # Dynamic budget form with real-time calculations
│   ├── BudgetList.php         # Paginated budget listing
│   ├── Actions/Logout.php
│   └── Settings/              # User settings (5 files)
│       ├── Profile.php
│       ├── Password.php
│       ├── Appearance.php
│       ├── DeleteUserForm.php
│       └── TwoFactor/RecoveryCodes.php
│
├── Mail/
│   └── BudgetSentMail.php    # Budget notification emails
│
├── Models/                    # Eloquent models (7 files)
│   ├── User.php              # User with company relationship
│   ├── Budget.php            # Budget with auto-numbering
│   ├── BudgetItem.php        # Budget line items
│   ├── Client.php            # Client/customer data
│   ├── Company.php           # Multi-tenant company
│   ├── WorkOrder.php         # Service orders
│   └── Concerns/
│       └── HasNumber.php     # Trait for auto-generated numbers
│
├── Providers/
│   ├── AppServiceProvider.php
│   └── FortifyServiceProvider.php
│
└── Services/
    └── BudgetCalculator.php  # Budget calculation logic
```

### `/resources` - Views and Assets

```
resources/
├── views/                    # 50+ Blade templates
│   ├── budgets/             # Budget CRUD views (4 files)
│   ├── work-orders/         # Work order CRUD views (4 files)
│   ├── livewire/            # Livewire component templates
│   │   ├── budget-form.blade.php
│   │   ├── budget-list.blade.php
│   │   ├── settings/        # Settings pages
│   │   └── auth/            # Authentication pages (7 files)
│   ├── components/          # Reusable Blade components
│   │   └── layouts/         # App and Auth layouts
│   ├── emails/
│   │   └── budget-sent.blade.php
│   ├── pdf/
│   │   └── budget.blade.php # Budget PDF template
│   └── public/
│       └── budget-show.blade.php # Public budget view
│
├── css/
│   └── app.css             # Tailwind + Flux styling
│
└── js/
    └── app.js              # Minimal JavaScript
```

### `/routes` - HTTP Routes

```
routes/
├── web.php                 # Main routing (68 lines)
└── console.php            # Artisan commands
```

### `/database` - Schema and Data

```
database/
├── migrations/             # 11 migrations
├── factories/             # 4 factories (User, Budget, Client, WorkOrder)
└── seeders/               # 6 seeders
```

### `/tests` - Test Suite

```
tests/
├── Feature/               # Feature tests (11 files)
│   ├── Auth/             # Authentication tests (6 files)
│   └── Settings/         # Settings tests (3 files)
└── Unit/                 # Unit tests
```

---

## Database Schema

### Core Tables

**users**
- Standard Laravel user fields
- `company_id` - Multi-tenant relationship
- Two-factor fields: `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`

**companies**
- `user_id` - Owner relationship
- `name`, `fantasy_name` - Business names
- `document` - Unique business identifier
- `logo_path` - Company branding

**clients**
- `company_id` - Belongs to company
- Contact info: `name`, `email`, `phone`
- Address: `street`, `city`, `state`, `zip`

**budgets**
- `company_id`, `client_id` - Relationships
- `number` - Auto-generated (e.g., "BUD-CP-20251123100000")
- Financial fields: `subtotal`, `discount_value`, `discount_type`, `tax_value`, `tax_type`, `additional_fees`, `total`
- `status` - Enum: draft, sent, approved, rejected, expired
- `token` - Unique token for public sharing

**budget_items**
- `budget_id` - Parent relationship
- `name` - Item name/title
- `description` - Detailed description
- `quantity`, `unit_price` - Basic pricing
- `discount_value`, `discount_type` - Item-level discounts
- `tax_value`, `tax_type` - Item-level taxes
- `total` - Calculated total

**work_orders**
- `company_id`, `client_id`, `budget_id` - Relationships
- `number` - Auto-generated (e.g., "OS-CP-20251123100001")
- `status` - Enum: open, in_progress, completed, delivered, canceled

### Relationships

```
User ──┬─> Company (owner)
       └─> Company (member via company_id)

Company ──┬─> User (many users)
          ├─> Client
          ├─> Budget
          └─> WorkOrder

Budget ──┬─> Company
         ├─> Client
         ├─> BudgetItem (many)
         └─> WorkOrder (optional)

WorkOrder ──┬─> Company
            ├─> Client
            └─> Budget (optional)
```

---

## Key Features and Patterns

### 1. Multi-Tenant Architecture
- Every resource (Budget, WorkOrder) belongs to a Company
- Users belong to Companies via `company_id`
- Use `hasManyThrough` for querying user's resources
- Always scope queries by company when implementing features

### 2. Auto-Numbering Pattern (HasNumber Trait)

Models using this trait (Budget, WorkOrder) automatically generate unique numbers:

```php
// Format: {PREFIX}-{COMPANY_INITIALS}-{TIMESTAMP}
// Examples:
//   Budget: BUD-CP-20251123100000
//   WorkOrder: OS-CP-20251123100001

// Implemented in app/Models/Concerns/HasNumber.php
// Auto-applied on model creation
```

**When to use**: Add this trait to any model that needs unique reference numbers.

### 3. Budget Calculation Service

The `BudgetCalculator` service (app/Services/BudgetCalculator.php) handles all financial calculations:

```php
// Calculates:
// 1. Subtotal from items
// 2. Discount (percentage or fixed amount)
// 3. Tax (percentage or fixed amount)
// 4. Additional fees
// 5. Final total

// Always use this service for budget totals
// Never calculate totals manually in controllers/views
```

### 4. Public Budget Sharing

Budgets can be shared publicly via unique tokens without authentication:

```php
// Routes: /o/{token}
// Features:
//   - View budget details
//   - Approve/reject budget
//   - Download PDF
//   - No login required

// Token generation: Budget model automatically creates on save
```

**Security**: Tokens are 32-character random strings, stored in `budgets.token` column.

### 5. Livewire Real-Time Components

**BudgetForm** (app/Livewire/BudgetForm.php):
- Real-time calculations using `wire:model.live`
- Dynamic item addition/removal
- Integrates with BudgetCalculator service
- Validation on save

**Pattern for new forms**: Follow BudgetForm structure for reactive forms with calculations.

### 6. Workflow Status Management

**Budget Statuses**: draft → sent → approved/rejected/expired
**WorkOrder Statuses**: open → in_progress → completed → delivered (or canceled)

**Converting budgets**: Approved budgets can be converted to work orders via `POST /budgets/{budget}/convert`

---

## Development Workflows

### Initial Setup

```bash
# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
touch database/database.sqlite
php artisan migrate
php artisan db:seed

# Build assets
npm run build
```

### Development Server

```bash
# Option 1: Using composer script (runs server + queue + vite concurrently)
composer dev

# Option 2: Manual (run in separate terminals)
php artisan serve
php artisan queue:listen --tries=1
npm run dev
```

### Testing

```bash
# Run all tests
composer test
# or
php artisan test

# Run specific test file
php artisan test tests/Feature/Auth/AuthenticationTest.php

# Run with coverage
php artisan test --coverage
```

**Test Configuration**:
- In-memory SQLite (`:memory:`)
- Array mail driver (inspect with `Mail::fake()`)
- Sync queue (immediate execution)
- 4 bcrypt rounds (faster)

### Code Style

```bash
# Fix code style issues
./vendor/bin/pint

# Check without fixing
./vendor/bin/pint --test
```

**Standard**: Laravel Pint (PSR-12 based)

### Database Operations

```bash
# Fresh migration
php artisan migrate:fresh

# Fresh with seeders
php artisan migrate:fresh --seed

# Create new migration
php artisan make:migration create_table_name

# Create model with migration and factory
php artisan make:model ModelName -mf
```

---

## Coding Conventions

### Models

1. **Always define fillable or guarded**
```php
protected $fillable = ['name', 'email', ...];
```

2. **Use casts for dates and enums**
```php
protected $casts = [
    'status' => 'string',
    'total' => 'decimal:2',
    'created_at' => 'datetime',
];
```

3. **Define relationships explicitly**
```php
public function company(): BelongsTo
{
    return $this->belongsTo(Company::class);
}
```

4. **Use concerns for shared behavior**
- Example: `HasNumber` trait for auto-numbering
- Keep traits in `app/Models/Concerns/`

### Controllers

1. **Keep controllers thin**
- Delegate business logic to services
- Use form requests for validation
- Return views or JSON responses

2. **Use route model binding**
```php
public function show(Budget $budget)
{
    // Laravel automatically finds budget by ID
}
```

3. **Authorize actions**
```php
$this->authorize('update', $budget);
```

### Livewire Components

1. **Use `wire:model.live` for real-time updates**
```blade
<input type="text" wire:model.live="form.name">
```

2. **Validate on submission**
```php
public function save()
{
    $validated = $this->validate([
        'form.name' => 'required|string|max:255',
    ]);

    // Save logic
}
```

3. **Use computed properties for derived data**
```php
#[Computed]
public function total()
{
    return $this->items->sum('total');
}
```

### Views and Blade

1. **Use Flux components**
```blade
<flux:button>Click me</flux:button>
<flux:input wire:model="form.name" />
```

2. **Follow layout structure**
- Auth pages: `@extends('components.layouts.auth')`
- App pages: `@extends('components.layouts.app')`

3. **Portuguese localization**
- All UI text in Portuguese
- Use proper translation keys for future i18n

### Services

1. **Single responsibility**
- Each service handles one domain concept
- Example: BudgetCalculator only calculates totals

2. **Dependency injection**
```php
public function __construct(
    private BudgetCalculator $calculator
) {}
```

### Testing

1. **Feature test structure**
```php
test('user can create budget', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->post('/budgets', [
            'client_id' => Client::factory()->create()->id,
            // ...
        ]);

    $response->assertRedirect();
    expect(Budget::count())->toBe(1);
});
```

2. **Use factories for test data**
```php
$budget = Budget::factory()->create();
```

3. **Test authentication and authorization**
```php
test('guest cannot access dashboard', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});
```

---

## Common Tasks

### Adding a New Feature

1. **Plan database changes**
```bash
php artisan make:migration create_feature_table
# Edit migration file
php artisan migrate
```

2. **Create model**
```bash
php artisan make:model Feature -f
# Add fillable, casts, relationships
```

3. **Create controller or Livewire component**
```bash
# Controller
php artisan make:controller FeatureController

# Livewire component
php artisan make:livewire FeatureComponent
```

4. **Add routes**
```php
// routes/web.php
Route::get('/features', [FeatureController::class, 'index']);
```

5. **Create views**
```bash
# Create resources/views/features/index.blade.php
```

6. **Write tests**
```bash
php artisan make:test FeatureTest
```

### Adding to Existing Models

**Example: Adding a field to Budget**

1. Create migration:
```bash
php artisan make:migration add_field_to_budgets_table
```

2. Edit migration:
```php
Schema::table('budgets', function (Blueprint $table) {
    $table->string('new_field')->nullable();
});
```

3. Update model:
```php
// Add to $fillable
protected $fillable = [..., 'new_field'];

// Add cast if needed
protected $casts = [..., 'new_field' => 'string'];
```

4. Update views and forms

5. Update tests

### Creating PDF Templates

Follow the pattern in `resources/views/pdf/budget.blade.php`:
- Use inline styles (no external CSS)
- Keep layout simple
- Test with `dompdf` (Laravel's default)

### Adding Public Routes

For features that don't require authentication:

1. Use unique tokens (32-char random strings)
2. Create dedicated controller (e.g., `BudgetPublicController`)
3. Add routes without `auth` middleware
4. Validate token in controller

---

## Security Considerations

### Authentication
- Laravel Fortify handles all auth flows
- Two-factor authentication available
- Email verification enabled
- Rate limiting: 5 attempts/minute on login and 2FA

### Authorization
- Use Laravel policies for model authorization
- Always check permissions in controllers
- Scope queries by company (multi-tenant security)

### Input Validation
- Always validate user input
- Use form requests for complex validation
- Sanitize input for PDF generation

### Token Security
- Public tokens are 32-char random strings
- Stored hashed in database (if sensitive)
- No token reuse across different resource types

### SQL Injection
- Use Eloquent ORM (parameterized queries)
- Never concatenate user input into queries

---

## Performance Optimization

### Database
- Use pagination for lists (see BudgetList: 12 items/page)
- Add indexes to foreign keys and frequently queried columns
- Use eager loading to prevent N+1 queries:
```php
Budget::with('company', 'client', 'items')->get();
```

### Caching
- Cache config: `php artisan config:cache`
- Cache routes: `php artisan route:cache`
- Cache views: `php artisan view:cache`
- Use Redis for production caching

### Queue Jobs
- Use queues for emails and heavy operations
- Database driver configured (switch to Redis for production)

### Frontend
- Vite bundles assets efficiently
- Use `wire:model.lazy` for non-critical updates
- Minimize Livewire component state

---

## Deployment Checklist

### Pre-deployment
- [ ] Run tests: `composer test`
- [ ] Fix code style: `./vendor/bin/pint`
- [ ] Build assets: `npm run build`
- [ ] Update `.env` for production
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Generate app key: `php artisan key:generate`

### Database
- [ ] Run migrations: `php artisan migrate --force`
- [ ] Seed if needed: `php artisan db:seed --force`
- [ ] Backup database before migrations

### Optimization
- [ ] Cache config: `php artisan config:cache`
- [ ] Cache routes: `php artisan route:cache`
- [ ] Cache views: `php artisan view:cache`
- [ ] Optimize autoloader: `composer install --optimize-autoloader --no-dev`

### Security
- [ ] Set strong `APP_KEY`
- [ ] Configure HTTPS
- [ ] Set secure session cookies
- [ ] Configure CORS if needed
- [ ] Set up rate limiting
- [ ] Review file permissions

### Queue and Jobs
- [ ] Configure queue worker: `php artisan queue:work --daemon`
- [ ] Set up supervisor for queue workers
- [ ] Monitor failed jobs

---

## Troubleshooting

### Common Issues

**"Class not found" errors**
```bash
composer dump-autoload
```

**Asset not found (404)**
```bash
npm run build
php artisan view:clear
```

**Database connection issues**
```bash
# Check .env file
# Verify database file exists: database/database.sqlite
touch database/database.sqlite
php artisan migrate
```

**Livewire not updating**
```bash
php artisan view:clear
php artisan cache:clear
```

**Tests failing**
```bash
# Ensure test database is clean
php artisan config:clear
composer test
```

### Debug Mode

Enable detailed errors in `.env`:
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

Use Laravel Debugbar (add to dev dependencies):
```bash
composer require --dev barryvdh/laravel-debugbar
```

---

## Resources

### Documentation
- Laravel: https://laravel.com/docs/12.x
- Livewire: https://livewire.laravel.com/docs
- Flux: https://flux.laravel.com/docs
- Tailwind CSS: https://tailwindcss.com/docs
- Pest PHP: https://pestphp.com/docs

### Project-Specific
- Main routes: `routes/web.php:1`
- Fortify config: `config/fortify.php:1`
- Database config: `config/database.php:1`
- Test config: `tests/Pest.php:1`

---

## AI Assistant Guidelines

### Best Practices

1. **Always read files before modifying**
   - Never propose changes without understanding existing code
   - Check for existing patterns and conventions

2. **Follow existing patterns**
   - Use HasNumber trait for auto-numbering
   - Use BudgetCalculator pattern for complex calculations
   - Follow Livewire component structure

3. **Maintain multi-tenant integrity**
   - Always scope queries by company
   - Test with multiple companies
   - Never leak data between companies

4. **Write tests**
   - Add feature tests for new features
   - Follow existing test patterns in `tests/Feature/`
   - Use Pest's `test()` function, not `it()`

5. **Keep it simple**
   - Don't over-engineer
   - Only add requested features
   - Avoid premature abstractions

6. **Respect the stack**
   - Use Livewire for reactive UI
   - Use Flux components for UI elements
   - Keep JavaScript minimal
   - Use Blade for static content

### When Adding Features

- [ ] Check if similar feature exists
- [ ] Follow multi-tenant pattern
- [ ] Use existing services (BudgetCalculator, etc.)
- [ ] Add validation
- [ ] Write tests
- [ ] Update this file if adding new patterns
- [ ] Keep Portuguese localization consistent

### Code Review Checklist

- [ ] Code follows Laravel conventions
- [ ] Models have proper relationships defined
- [ ] Controllers are thin
- [ ] Business logic in services
- [ ] Views use Flux components
- [ ] Tests cover new functionality
- [ ] No N+1 query issues
- [ ] Multi-tenant scope applied
- [ ] Security considerations addressed
- [ ] Portuguese UI text

---

## Version Information

- **Laravel**: 12.0
- **PHP**: 8.2+
- **Livewire**: 3.x
- **Flux**: 2.1.1
- **Tailwind CSS**: 4.0
- **Node**: Compatible with Vite 7.0

**Last Updated**: 2025-11-23
**Project Language**: Portuguese (Brazilian)
**License**: MIT
