<?php namespace App\Jobs;

use App\ErrorLog;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Controllers\OrderController;
use Osiset\ShopifyApp\Objects\Values\ShopDomain;
use stdClass;

class OrdersCreateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Shop's myshopify domain
     *
     * @var ShopDomain|string
     */
    public $shopDomain;

    /**
     * The webhook data
     *
     * @var object
     */
    public $data;

    /**
     * Create a new job instance.
     *
     * @param string   $shopDomain The shop's myshopify domain.
     * @param stdClass $data       The webhook data (JSON decoded).
     *
     * @return void
     */
    public function __construct($shopDomain, $data)
    {
        $this->shopDomain = $shopDomain;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Convert domain
//        $this->shopDomain = ShopDomain::fromNative($this->shopDomain);
//
//        $shop = User::where('name', $this->shopDomain->toNative())->first();
//        $order = json_decode(json_encode($this->data), false);
//        $orderController = new OrderController;
//        $orderController->createShopifyOrder($order, $shop);

        try{

            $this->shopDomain = ShopDomain::fromNative($this->shopDomain);
            $shop = User::where('name', $this->shopDomain->toNative())->first();
            $order = json_decode(json_encode($this->data), false);
            $orderController = new OrderController;
            $orderController->createShopifyOrders($order, $shop);

        }
        catch(\Exception $e) {
            $log = new ErrorLog();
            $log->message = "Order create Job ". $e->getMessage();
            $log->save();
        }

        // Do what you wish with the data
        // Access domain name as $this->shopDomain->toNative()
    }
}
