# Iceland Electronic ID Integration for Laravel

This package provides integration for Iceland's electronic ID system with Laravel Socialite.

## Installation

1. **Require the package via Composer:**

   ```bash
   composer require advanced-solutions/iceland-electronic-id
   ```
2. **Publish the configuration file:**
    ```bash
    php artisan vendor:publish --provider="AdvancedSolutions\IcelandElectronicId\IslandsServiceProvider"
    ```
3. **Add the Iceland Electronic ID configuration to your:**
    ```dotenv
    ISLANDS_CLIENT_ID=your-client-id
    ISLANDS_CLIENT_SECRET=your-client-secret
    ISLANDS_REDIRECT_URI=your-redirect-uri
    ```
4. **Add the configuration to `config/services.php:`**
    ```php
    'islands' => [
    'client_id' => env('ISLANDS_CLIENT_ID'),
    'client_secret' => env('ISLANDS_CLIENT_SECRET'),
    'redirect' => env('ISLANDS_REDIRECT_URI'),
    ],
    ```

## Usage

1. **Add routes to your api.php (for example):**
    ```php
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Auth\LoginController;
    
    Route::get('login/islands', [LoginController::class, 'redirectToIslands']);
    Route::get('login/islands/callback', [LoginController::class, 'handleIslandsCallback']);
    ```
2. **Update your LoginController:**
    ```php

   class AuthController extends Controller
   {
   protected $islandsService;
   
       public function __construct(IslandsService $islandsService)
       {
           $this->islandsService = $islandsService;
       }
   
       public function redirectToIslands()
       {
           $query = http_build_query([
               'client_id' => config('islands.client_id'),
               'redirect_uri' => config('islands.redirect_uri'),
               'response_type' => 'code',
               'scope' => 'openid profile',
               'state' => csrf_token(),
           ]);
   
           return redirect('https://identity-server.staging01.devland.is/connect/authorize?' . $query);
       }
   
       public function handleIslandsCallback(Request $request)
       {
           $code = $request->get('code');
   
           try {
               $tokenData = $this->islandsService->authenticate($code);
               $userInfo = $this->islandsService->getUserInfo($tokenData['access_token']);
   
               // Find or create user logic
               $user = User::firstOrCreate(
                   ['email' => $userInfo['email']],
                   ['name' => $userInfo['name']]
               );
   
               Auth::login($user, true);
   
               return redirect()->intended('dashboard');
           } catch (\Exception $e) {
               return redirect('/login')->withErrors(['error' => $e->getMessage()]);
           }
       }
   }
    ```
3. **Update the User model if necessary:**
    ```php
    ...
    class User extends Authenticatable
    {
    use Notifiable;
    
    /**
     * The attributes that are mass assignable.
       *
       * @var array
       */
    protected $fillable = [
          'name', 'email', 'password',
    ];
    
    /**
     * The attributes that should be hidden for arrays.
       *
       * @var array
       */
    protected $hidden = [
          'password', 'remember_token',
    ];
    }
      ```
   
## Testing

To test the Iceland Electronic ID integration, simply navigate to the login route:
   ```text
      http://your-app-url/login/islands
   ```