<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\ContainerTransaction;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Location;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductionBatch;
use App\Models\Route;
use App\Models\StandingOrder;
use App\Models\StandingOrderItem;
use App\Models\Task;
use App\Models\Truck;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command?->info('Seeding Living Water demo data (20L dispenser only)...');

        // --- Demo login -----------------------------------------------------
        User::updateOrCreate(
            ['email' => 'demo@livingwater.co.tz'],
            ['name' => 'Living Water Demo', 'password' => bcrypt('demo1234')],
        );

        // --- Locations ------------------------------------------------------
        $factory = Location::create(['name' => 'Living Water Factory — Mikocheni', 'type' => 'factory', 'address' => 'Plot 44, Mikocheni Light Industrial Area', 'region' => 'Dar es Salaam']);
        $depotKin = Location::create(['name' => 'Kinondoni Depot', 'type' => 'depot', 'address' => 'Kawe, Kinondoni', 'region' => 'Dar es Salaam']);
        $depotTem = Location::create(['name' => 'Temeke Depot', 'type' => 'depot', 'address' => "Chang'ombe Road, Temeke", 'region' => 'Dar es Salaam']);
        $locations = [$factory, $depotKin, $depotTem];

        // --- Routes ---------------------------------------------------------
        $routeNames = ['Kinondoni Route', 'Ilala Route', 'Temeke Route', 'Ubungo Route', 'Kigamboni Route', 'Bagamoyo Route'];
        $routes = [];
        foreach ($routeNames as $rn) {
            $routes[] = Route::create(['name' => $rn, 'region' => 'Dar es Salaam', 'description' => 'Daily distribution route']);
        }

        // --- Drivers --------------------------------------------------------
        $driverNames = ['Juma Mwakalinga', 'Rashid Omari', 'Baraka Mushi', 'Said Khamis', 'Emmanuel Lyimo', 'Hamisi Juma'];
        $drivers = [];
        foreach ($driverNames as $dn) {
            $drivers[] = Driver::create(['name' => $dn, 'phone' => $this->phone(7), 'license_no' => 'TZ-DL-'.random_int(100000, 999999)]);
        }

        // --- The single product: 20L returnable dispenser bottle -----------
        $bottle = Product::create([
            'sku' => 'LW-20L',
            'name' => 'Living Water 20L Dispenser Bottle',
            'size_label' => '20L',
            'type' => 'dispenser',
            'unit_price' => 2500,          // refill price
            'is_returnable' => true,
            'deposit_amount' => 12000,     // deposit held per bottle until returned
            'reorder_level' => 200,
        ]);

        // --- Trucks ---------------------------------------------------------
        $trucks = [];
        foreach ($drivers as $i => $driver) {
            $trucks[] = Truck::create([
                'plate_no' => sprintf('T %03d %s', random_int(100, 999), chr(65 + $i).chr(65 + ($i + 3) % 26).chr(65 + ($i + 7) % 26)),
                'name' => 'Delivery Truck '.($i + 1),
                'capacity' => [600, 800, 1000][$i % 3],
                'driver_id' => $driver->id,
            ]);
        }

        // --- Customers + contacts ------------------------------------------
        $customerSeed = [
            ['Mlimani City Food Court', 'hotel'], ['Serengeti Breeze Hotel', 'hotel'], ['Kariakoo Mini Mart', 'retailer'],
            ['Oyster Bay Offices Ltd', 'office'], ['Masaki Grocers', 'retailer'], ['Coastal Logistics HQ', 'office'],
            ['Sea Cliff Restaurant', 'hotel'], ['Mwenge Wholesale Depot', 'distributor'], ['Tegeta Water Point', 'distributor'],
            ['Sinza Corner Shop', 'retailer'], ['Mbezi Beach Apartments', 'household'], ['Victoria Supermarket', 'retailer'],
            ['Posta Business Centre', 'office'], ['Upanga Clinic', 'office'], ['Kigamboni Ferry Kiosk', 'retailer'],
            ['Boko Secondary School', 'office'], ['Mikocheni Pharmacy', 'retailer'], ['Regent Estate Villas', 'household'],
            ['Bagamoyo Road Distributors', 'distributor'], ['Tandale Market Vendors', 'distributor'], ['Kawe Beach Lodge', 'hotel'],
            ['Ilala Boma Canteen', 'office'], ['Temeke General Store', 'retailer'], ['Kinondoni Household — Mama Neema', 'household'],
            ['Ubungo Bus Terminal Cafe', 'hotel'], ['Msasani Peninsula Homes', 'household'], ['Gongo la Mboto Shop', 'retailer'],
            ["Chang'ombe Industrial Canteen", 'office'], ['Kijitonyama Water Agents', 'distributor'], ['Mikumi Guest House', 'hotel'],
        ];
        $customers = [];
        foreach ($customerSeed as $i => [$name, $type]) {
            $tier = $type === 'distributor' ? 'wholesale' : (in_array($type, ['office', 'hotel'], true) ? 'contract' : 'standard');
            $creditLimit = in_array($type, ['distributor', 'office', 'hotel'], true) ? random_int(5, 20) * 100000 : random_int(0, 3) * 50000;
            $c = Customer::create([
                'name' => $name,
                'type' => $type,
                'route_id' => $routes[$i % count($routes)]->id,
                'phone' => $this->phone(6),
                'email' => strtolower(preg_replace('/[^a-z0-9]/i', '', explode(' ', $name)[0])).'@example.co.tz',
                'address' => 'Dar es Salaam',
                'price_tier' => $tier,
                'credit_limit' => $creditLimit,
                'balance' => 0,
            ]);
            Contact::create(['customer_id' => $c->id, 'name' => 'Procurement Officer', 'role' => 'Purchasing', 'phone' => $c->phone, 'email' => $c->email, 'is_primary' => true]);
            if ($i % 3 === 0) {
                Contact::create(['customer_id' => $c->id, 'name' => 'Accounts Desk', 'role' => 'Finance', 'email' => 'accounts.'.$c->id.'@example.co.tz', 'is_primary' => false]);
            }
            $customers[] = $c;
        }

        // --- Production batches of 20L bottles (last 90 days) --------------
        for ($d = 90; $d >= 0; $d -= 3) {
            $day = Carbon::now()->subDays($d);
            ProductionBatch::create([
                'product_id' => $bottle->id,
                'location_id' => $factory->id,
                'batch_code' => 'B'.$day->format('ymd').'-20L',
                'quantity' => random_int(400, 1500),
                'produced_on' => $day->toDateString(),
                'status' => $d <= 2 ? 'in_progress' : 'completed',
            ]);
        }

        // --- Orders + items + container movements + invoices + payments ----
        $orderNo = 1000;
        $invoiceNo = 5000;
        $balances = [];
        $price = ['standard' => 2500.0, 'contract' => 2250.0, 'wholesale' => 2050.0];

        for ($d = 90; $d >= 0; $d--) {
            $day = Carbon::now()->subDays($d);
            $todaysOrders = random_int(2, 5);
            for ($o = 0; $o < $todaysOrders; $o++) {
                $customer = $customers[array_rand($customers)];
                $truck = $trucks[array_rand($trucks)];
                $status = $d === 0 ? 'pending' : ($d <= 1 ? 'loaded' : (random_int(0, 100) < 92 ? 'delivered' : 'cancelled'));

                $qty = match ($customer->type) {
                    'distributor' => random_int(20, 60),
                    'hotel', 'office' => random_int(8, 25),
                    'retailer' => random_int(5, 15),
                    default => random_int(2, 8),
                };
                $unit = $price[$customer->price_tier] ?? 2500.0;
                $total = round($unit * $qty, 2);

                $order = Order::create([
                    'order_no' => 'SO-'.(++$orderNo),
                    'customer_id' => $customer->id,
                    'route_id' => $customer->route_id,
                    'truck_id' => $truck->id,
                    'order_date' => $day->toDateString(),
                    'delivery_date' => $status === 'delivered' ? $day->toDateString() : null,
                    'status' => $status,
                    'is_standing' => false,
                    'total' => $total,
                ]);

                // Empties collected on delivery — usually fewer than issued, so
                // every customer steadily accumulates bottles still "in the field".
                $collected = $status === 'delivered' ? (int) floor($qty * (random_int(55, 90) / 100)) : 0;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $bottle->id,
                    'quantity' => $qty,
                    'unit_price' => $unit,
                    'line_total' => $total,
                    'returnable_collected' => $collected,
                ]);

                if ($status === 'delivered') {
                    ContainerTransaction::create(['customer_id' => $customer->id, 'product_id' => $bottle->id, 'txn_date' => $day->toDateString(), 'type' => 'issued', 'quantity' => $qty, 'deposit_delta' => $qty * (float) $bottle->deposit_amount, 'notes' => 'Issued with order '.$order->order_no]);
                    if ($collected > 0) {
                        ContainerTransaction::create(['customer_id' => $customer->id, 'product_id' => $bottle->id, 'txn_date' => $day->toDateString(), 'type' => 'returned', 'quantity' => $collected, 'deposit_delta' => -1 * $collected * (float) $bottle->deposit_amount, 'notes' => 'Empties collected on delivery of '.$order->order_no]);
                    }

                    // Invoice the refill; leave some unpaid to build receivables.
                    $due = $day->copy()->addDays(14);
                    $roll = random_int(0, 100);
                    if ($roll < 55) {
                        $invStatus = 'paid';
                        $paid = $total;
                    } elseif ($roll < 70) {
                        $invStatus = 'partial';
                        $paid = round($total * 0.5, 2);
                    } elseif ($due->isPast()) {
                        $invStatus = 'overdue';
                        $paid = 0.0;
                    } else {
                        $invStatus = 'unpaid';
                        $paid = 0.0;
                    }
                    $invoice = Invoice::create(['invoice_no' => 'INV-'.(++$invoiceNo), 'customer_id' => $customer->id, 'order_id' => $order->id, 'issue_date' => $day->toDateString(), 'due_date' => $due->toDateString(), 'amount' => $total, 'amount_paid' => $paid, 'status' => $invStatus]);
                    if ($paid > 0) {
                        Payment::create(['invoice_id' => $invoice->id, 'customer_id' => $customer->id, 'paid_on' => $day->copy()->addDays(random_int(1, 10))->toDateString(), 'amount' => $paid, 'method' => ['cash', 'mpesa', 'bank'][random_int(0, 2)], 'reference' => 'PAY-'.random_int(100000, 999999)]);
                    }
                    $balances[$customer->id] = ($balances[$customer->id] ?? 0) + ($total - $paid);
                }
            }
        }

        foreach ($balances as $cid => $bal) {
            Customer::whereKey($cid)->update(['balance' => round($bal, 2)]);
        }

        // --- Standing orders (recurring refill agreements) ------------------
        $freqs = ['daily', 'weekly', 'biweekly', 'monthly'];
        foreach (array_slice($customers, 0, 14) as $i => $customer) {
            $so = StandingOrder::create([
                'customer_id' => $customer->id,
                'route_id' => $customer->route_id,
                'frequency' => $freqs[$i % count($freqs)],
                'next_delivery' => Carbon::now()->addDays(random_int(1, 7))->toDateString(),
                'active' => $i % 7 !== 0,
                'notes' => 'Recurring 20L refill agreement',
            ]);
            StandingOrderItem::create(['standing_order_id' => $so->id, 'product_id' => $bottle->id, 'quantity' => random_int(5, 40)]);
        }

        // --- CRM leads (pipeline) ------------------------------------------
        $leadSeed = [
            ['Dar Marine Suppliers', 'proposal'], ['Kariakoo Fresh Juices', 'qualified'], ['Mikocheni Gym & Spa', 'contacted'],
            ['Bunju Housing Estate', 'new'], ['Airport Lounge Services', 'proposal'], ['Kigamboni Beach Resort', 'won'],
            ['Mabibo Student Hostels', 'new'], ['Tabata Trading Co.', 'qualified'], ['Mwananyamala Hospital Canteen', 'contacted'],
            ['Kunduchi Wet n Wild Kiosks', 'lost'], ['Goba Residents Association', 'new'], ['Ferry Terminal Vendors', 'proposal'],
        ];
        $sources = ['referral', 'walk_in', 'campaign', 'website'];
        $owners = ['Neema S.', 'Frank M.', 'Asha K.'];
        foreach ($leadSeed as $i => [$name, $stage]) {
            Lead::create([
                'name' => $name,
                'contact_name' => ['Mr. Mollel', 'Ms. Kibwana', 'Mr. Shabani', 'Ms. Juma'][$i % 4],
                'phone' => $this->phone(7),
                'email' => strtolower(preg_replace('/[^a-z0-9]/i', '', explode(' ', $name)[0])).'@prospect.co.tz',
                'source' => $sources[$i % count($sources)],
                'stage' => $stage,
                'estimated_value' => random_int(2, 30) * 100000,
                'route_id' => $routes[$i % count($routes)]->id,
                'owner' => $owners[$i % count($owners)],
                'notes' => 'Prospective 20L refill account',
            ]);
        }

        // --- Follow-up tasks ------------------------------------------------
        $leads = Lead::all();
        $taskTitles = ['Call to confirm refill order', 'Deliver sample bottles', 'Send price quotation', 'Collect overdue empty bottles', 'Follow up on overdue invoice', 'Schedule site visit'];
        for ($i = 0; $i < 16; $i++) {
            $onLead = $i % 2 === 0 && $leads->isNotEmpty();
            Task::create([
                'title' => $taskTitles[$i % count($taskTitles)],
                'due_date' => Carbon::now()->addDays(random_int(-3, 10))->toDateString(),
                'done' => random_int(0, 100) < 35,
                'customer_id' => $onLead ? null : $customers[array_rand($customers)]->id,
                'lead_id' => $onLead ? $leads->random()->id : null,
                'assigned_to' => $owners[$i % count($owners)],
                'notes' => null,
            ]);
        }

        // --- Expenses -------------------------------------------------------
        $categories = ['raw_materials', 'fuel', 'maintenance', 'salaries', 'utilities', 'other'];
        for ($d = 90; $d >= 0; $d -= 2) {
            $day = Carbon::now()->subDays($d);
            $cat = $categories[array_rand($categories)];
            Expense::create([
                'location_id' => $locations[array_rand($locations)]->id,
                'expense_date' => $day->toDateString(),
                'category' => $cat,
                'amount' => match ($cat) {
                    'salaries' => random_int(8, 25) * 100000,
                    'raw_materials' => random_int(5, 40) * 100000,
                    'fuel' => random_int(1, 8) * 50000,
                    default => random_int(1, 10) * 50000,
                },
                'notes' => ucfirst(str_replace('_', ' ', $cat)).' expense',
            ]);
        }

        $this->command?->info('Done. Login: demo@livingwater.co.tz / demo1234');
    }

    private function phone(int $prefix): string
    {
        return '+2556'.$prefix.str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}
