## General code instructions

- Don't generate code comments above the methods or code blocks if they are already commented. Don't add docblock comments when defining variables, unless instructed to, like `/** @var \App\Models\User $currentUser */`. Generate comments only for something that needs extra explanation for the reasons why that code was written. If defining dockblocks for variables, always use the FQDN of the variable's type.
- For new features, you MUST generate Pest automated tests.
- For library documentation, if some library is not available in Laravel Boost 'search-docs', always use context7. Automatically use the Context7 MCP tools to resolve library id and get library docs without me having to explicitly ask.

---

## PHP instructions

- In PHP, use `match` operator over `switch` whenever possible
- Generate Enums always in the folder corresponding namespace folder, for example `app/Core/Enums`, not in the main `app/` folder, unless instructed differently.
- Always use Enum value as the default in the migration if column values are from the enum. Always casts this column to the enum type in the Model.
- Don't create temporary variables like `$currentUser = auth()->user()` if that variable is used only one time.
- Always use Enum where possible instead of hardcoded string values, if Enum class exists. For example, in Blade files, and in the tests when creating data if field is casted to Enum then use that Enum instead of hardcoding the value.

---

## Laravel instructions

- Using Services in Controllers: if Service class is used only in ONE method of Controller, inject it directly into that method with type-hinting. If Service class is used in MULTIPLE methods of Controller, initialize it in Constructor.
- **Eloquent Observers** should be registered in Eloquent Models with PHP Attributes, and not in AppServiceProvider. Example: `#[ObservedBy([UserObserver::class])]` with `use Illuminate\Database\Eloquent\Attributes\ObservedBy;` on top
- Aim for "slim" Controllers and put larger logic pieces in Action classes
- Use Laravel facades instead of helper classes. Examples: use `Auth::id()` instead of `auth()->id()`.
- In Livewire projects, don't use Livewire Volt. Only Livewire class components.
- When adding columns in a migration, update the model's `$fillable` array to include those new attributes, unless they are
  foreign key columns referencing another table. In that case, generate the corresponding relationship method. For example, if you define the following column `$table->foreignIdFor(User::class)->constrained()`, generate the method `public function user(): BelongsTo { return $this->belongsTo(User::class); }` in the corresponding class.
- Never chain multiple migration-creating commands (e.g., `make:model -m`, `make:migration`) with `&&` or `;` — they may get identical timestamps. Run each command separately and wait for completion before running the next.
- Enums: If a PHP Enum exists for a domain concept, always use its cases (or their `->value`) instead of raw strings everywhere — routes, middleware, migrations, seeds, configs, and UI defaults.
- Controllers: Don't create invokable controllers. Instead, define a method with the corresponding name. For example, if a
  controller only shows a single resource, define `public function show(...)` instead of `public function __invoke(...)`.
- Always use Laravel's @session() directive instead of @if(session()) for displaying flash messages in Blade templates.
- In Blade files always use `@selected()` and `@checked()` directives instead of `selected` and `checked` HTML attributes. Good example: @selected(old('status') === App\Enums\ProjectStatus::Pending->value). Bad example: {{ old('status') === App\Enums\ProjectStatus::Pending->value ? 'selected' : '' }}.
- When generating migrations, only add the `up()` method. Do NOT add a `down()` method.

---

## Inertia instructions

---

## Testing instructions

### Before Writing Tests

1. **Check database schema** - Use `database-schema` tool to understand:
    - Which columns have defaults
    - Which columns are nullable
    - Foreign key relationship names

2. **Verify relationship names** - Read the model file to confirm:
    - Exact relationship method names (not assumed from column names)
    - Return types and related models

3. **Test realistic states** - Don't assume:
    - Empty model = all nulls (check for defaults)
    - `user_id` foreign key = `user()` relationship (could be `author()`, `employer()`, etc.)
    - When testing form submissions that redirect back with errors, assert that old input is preserved using `assertSessionHasOldInput()`.
