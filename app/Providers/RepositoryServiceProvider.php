<?php

namespace App\Providers;

use App\Repositories\Attribute\AttributeRepository;
use App\Repositories\Attribute\AttributeRepositoryInterface;
use App\Repositories\AttributeSet\AttributeSetRepository;
use App\Repositories\AttributeSet\AttributeSetRepositoryInterface;
use App\Repositories\Auth\AuthRepository;
use App\Repositories\Auth\AuthRepositoryInterface;
use App\Repositories\Banner\BannerRepository;
use App\Repositories\Banner\BannerRepositoryInterface;
use App\Repositories\Application\ApplicationRepository;
use App\Repositories\Application\ApplicationRepositoryInterface;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Cms\CmsRepository;
use App\Repositories\Cms\CmsRepositoryInterface;
use App\Repositories\Country\CountryRepository;
use App\Repositories\Country\CountryRepositoryInterface;
use App\Repositories\Customer\CustomerRepository;
use App\Repositories\Customer\CustomerRepositoryInterface;
use App\Repositories\Contents\ContentsRepository;
use App\Repositories\Contents\ContentsRepositoryInterface;
use App\Repositories\Currency\CurrencyRepository;
use App\Repositories\Currency\CurrencyRepositoryInterface;
use App\Repositories\Products\ProductsRepository;
use App\Repositories\Products\ProductsRepositoryInterface;
use App\Repositories\Settings\SettingsRepository;
use App\Repositories\Settings\SettingsRepositoryInterface;
use App\Repositories\State\StateRepository;
use App\Repositories\State\StateRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Cart\CartRepository;
use App\Repositories\Cart\CartRepositoryInterface;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\OrderReturn\OrderReturnRepository;
use App\Repositories\OrderReturn\OrderReturnRepositoryInterface;
use App\Repositories\Wishlist\WishlistRepository;
use App\Repositories\Wishlist\WishlistRepositoryInterface;
use App\Repositories\Pages\PagesRepository;
use App\Repositories\Pages\PagesRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(BannerRepositoryInterface::class, BannerRepository::class);
        $this->app->bind(CountryRepositoryInterface::class, CountryRepository::class);
        $this->app->bind(CurrencyRepositoryInterface::class, CurrencyRepository::class);
        $this->app->bind(ContentsRepositoryInterface::class, ContentsRepository::class);
        $this->app->bind(SettingsRepositoryInterface::class, SettingsRepository::class);
        $this->app->bind(StateRepositoryInterface::class, StateRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(AttributeRepositoryInterface::class, AttributeRepository::class);
        $this->app->bind(AttributeSetRepositoryInterface::class, AttributeSetRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(ProductsRepositoryInterface::class, ProductsRepository::class);
        $this->app->bind(ApplicationRepositoryInterface::class, ApplicationRepository::class);
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->bind(CartRepositoryInterface::class, CartRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(OrderReturnRepositoryInterface::class, OrderReturnRepository::class);
        $this->app->bind(WishlistRepositoryInterface::class, WishlistRepository::class);
        $this->app->bind(PagesRepositoryInterface::class, PagesRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
