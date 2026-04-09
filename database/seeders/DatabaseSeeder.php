<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\EscortProfile;
use App\Models\Post;
use App\Models\Review;
use App\Models\Tenant;
use App\Models\Thread;
use App\Models\TokenPackage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::create([
            'name' => 'Deutschland',
            'slug' => 'de',
            'domain' => 'localhost',
            'locale' => 'de',
            'currency' => 'EUR',
            'timezone' => 'Europe/Berlin',
            'token_price_cents' => 15,
            'is_active' => true,
            'settings' => [
                'country_code' => 'DE',
                'phone_prefix' => '+49',
                'features' => [
                    'show_prices' => true,
                    'show_price_filter' => true,
                    'show_contact_buttons' => true,
                    'show_service_tags' => true,
                ],
            ],
        ]);

        // Token Packages
        foreach ([
            ['name' => 'Starter', 'slug' => 'starter', 'tokens' => 100, 'price_cents' => 1500, 'badge_color' => '#8888a4', 'sort_order' => 1],
            ['name' => 'Standard', 'slug' => 'standard', 'tokens' => 250, 'price_cents' => 3500, 'badge_color' => '#e84393', 'sort_order' => 2],
            ['name' => 'Premium', 'slug' => 'premium', 'tokens' => 600, 'price_cents' => 7000, 'badge_color' => '#f9ca24', 'sort_order' => 3],
            ['name' => 'VIP', 'slug' => 'vip', 'tokens' => 1200, 'price_cents' => 12000, 'badge_color' => '#f9ca24', 'sort_order' => 4],
        ] as $pkg) {
            TokenPackage::create(array_merge($pkg, ['tenant_id' => $tenant->id]));
        }

        // Categories
        $cats = [];
        foreach ([
            ['name' => 'Escort Blogs', 'slug' => 'escort-blogs', 'type' => 'blogs', 'icon' => 'heart', 'description' => 'Persönliche Blogs der Escorts', 'sort_order' => 1],
            ['name' => 'Bewertungen', 'slug' => 'bewertungen', 'type' => 'reviews', 'icon' => 'star', 'description' => 'Erfahrungsberichte und Bewertungen', 'sort_order' => 2],
            ['name' => 'Allgemein', 'slug' => 'allgemein', 'type' => 'forum', 'icon' => 'chat-bubble-left-right', 'description' => 'Allgemeine Diskussionen', 'sort_order' => 3],
            ['name' => 'Neuigkeiten', 'slug' => 'neuigkeiten', 'type' => 'forum', 'icon' => 'megaphone', 'description' => 'Neuigkeiten und Ankündigungen', 'sort_order' => 4],
            ['name' => 'Tipps & Ratschläge', 'slug' => 'tipps', 'type' => 'forum', 'icon' => 'light-bulb', 'description' => 'Tipps für Neulinge und Erfahrene', 'sort_order' => 5],
        ] as $cat) {
            $cats[$cat['slug']] = Category::create(array_merge($cat, ['tenant_id' => $tenant->id]));
        }

        // City subcategories
        foreach (['Berlin', 'München', 'Hamburg', 'Frankfurt', 'Köln', 'Düsseldorf', 'Stuttgart', 'Hannover'] as $i => $city) {
            Category::create([
                'tenant_id' => $tenant->id,
                'name' => $city,
                'slug' => strtolower(str_replace(['ü', 'ö', 'ä'], ['ue', 'oe', 'ae'], $city)),
                'type' => 'reviews',
                'parent_id' => $cats['bewertungen']->id,
                'sort_order' => $i + 1,
            ]);
        }

        // Admin (tu usuario)
        $admin = User::create([
            'tenant_id' => $tenant->id, 'username' => 'fabri', 'name' => 'Fabri',
            'email' => 'admin@forumescort.de', 'password' => Hash::make('admin123'),
            'role' => 'admin', 'email_verified_at' => now(), 'token_balance' => 99999,
        ]);

        // Moderator
        User::create([
            'tenant_id' => $tenant->id, 'username' => 'moderator', 'name' => 'Moderator',
            'email' => 'mod@forumescort.de', 'password' => Hash::make('password'),
            'role' => 'moderator', 'email_verified_at' => now(), 'token_balance' => 5000,
        ]);

        // Escorts
        $escortData = [
            ['username' => 'sofia_berlin', 'name' => 'Sofia', 'email' => 'sofia@example.com', 'display_name' => 'Sofia', 'city' => 'Berlin', 'neighborhood' => 'Mitte', 'age' => 26, 'nationality' => 'Spanisch', 'languages' => ['de', 'es', 'en'], 'services' => ['Begleitung', 'Dinner Date', 'Massage', 'Reisebegleitung'], 'rates' => ['1h' => 200, '2h' => 350, '3h' => 500, 'overnight' => 1200], 'contact_telegram' => '@sofia_berlin', 'contact_phone' => '+49 176 11111111', 'is_verified' => true, 'featured' => true,
                'description' => 'Hallo! Ich bin Sofia, eine leidenschaftliche und elegante Begleiterin in Berlin-Mitte. Ich biete unvergessliche Momente in einer diskreten und angenehmen Atmosphäre.'],
            ['username' => 'luna_munich', 'name' => 'Luna', 'email' => 'luna@example.com', 'display_name' => 'Luna', 'city' => 'München', 'neighborhood' => 'Schwabing', 'age' => 24, 'nationality' => 'Deutsch', 'languages' => ['de', 'en'], 'services' => ['Begleitung', 'Dinner Date', 'Massage'], 'rates' => ['1h' => 180, '2h' => 320, '3h' => 450], 'contact_phone' => '+49 170 1234567', 'contact_telegram' => '@luna_munich', 'is_verified' => true, 'featured' => false,
                'description' => 'Hey, ich bin Luna! Eine natürliche und warmherzige Münchnerin, die es liebt, neue Menschen kennenzulernen.'],
            ['username' => 'mia_hamburg', 'name' => 'Mia', 'email' => 'mia@example.com', 'display_name' => 'Mia', 'city' => 'Hamburg', 'neighborhood' => 'St. Pauli', 'age' => 29, 'nationality' => 'Brasilianisch', 'languages' => ['de', 'pt', 'en'], 'services' => ['Begleitung', 'Massage', 'Tantramassage', 'Reisebegleitung'], 'rates' => ['1h' => 220, '2h' => 400, 'overnight' => 1500], 'contact_telegram' => '@mia_hamburg', 'contact_phone' => '+49 151 22222222', 'is_verified' => false, 'featured' => false,
                'description' => 'Olá! Ich bin Mia aus Hamburg. Exotisch, sinnlich und immer gut gelaunt.'],
            ['username' => 'elena_frankfurt', 'name' => 'Elena', 'email' => 'elena@example.com', 'display_name' => 'Elena', 'city' => 'Frankfurt', 'neighborhood' => 'Bahnhofsviertel', 'age' => 27, 'nationality' => 'Russisch', 'languages' => ['de', 'ru', 'en'], 'services' => ['Begleitung', 'Dinner Date', 'Events', 'Reisebegleitung'], 'rates' => ['1h' => 250, '2h' => 450, '3h' => 600, 'overnight' => 1800], 'contact_email' => 'elena.ffm@proton.me', 'contact_telegram' => '@elena_ffm', 'contact_phone' => '+49 157 33333333', 'is_verified' => true, 'featured' => true,
                'description' => 'Ich bin Elena, eine kultivierte und weltoffene Dame aus Frankfurt. Perfekt für ein elegantes Dinner.'],
            ['username' => 'anna_koeln', 'name' => 'Anna', 'email' => 'anna@example.com', 'display_name' => 'Anna', 'city' => 'Köln', 'neighborhood' => 'Altstadt', 'age' => 23, 'nationality' => 'Polnisch', 'languages' => ['de', 'pl', 'en'], 'services' => ['Begleitung', 'Dinner Date', 'Massage', 'GFE'], 'rates' => ['1h' => 160, '2h' => 280, '3h' => 400], 'contact_telegram' => '@anna_koeln', 'contact_phone' => '+49 160 44444444', 'is_verified' => true, 'featured' => false,
                'description' => 'Cześć! Ich bin Anna aus Köln. Jung, frech und voller Energie. Ich freue mich auf deine Nachricht!'],
            ['username' => 'nina_duesseldorf', 'name' => 'Nina', 'email' => 'nina@example.com', 'display_name' => 'Nina', 'city' => 'Düsseldorf', 'neighborhood' => 'Königsallee', 'age' => 31, 'nationality' => 'Deutsch', 'languages' => ['de', 'en', 'fr'], 'services' => ['Begleitung', 'Events', 'Dinner Date', 'Reisebegleitung', 'Übernachtung'], 'rates' => ['1h' => 300, '2h' => 550, 'overnight' => 2000], 'contact_phone' => '+49 172 55555555', 'contact_telegram' => '@nina_dus', 'is_verified' => true, 'featured' => true,
                'description' => 'Bonjour! Ich bin Nina, eine erfahrene und stilvolle Begleiterin aus Düsseldorf. High-Class Service mit Niveau.'],
        ];

        $escortUsers = [];
        foreach ($escortData as $ed) {
            $user = User::create([
                'tenant_id' => $tenant->id, 'username' => $ed['username'], 'name' => $ed['name'],
                'email' => $ed['email'], 'password' => Hash::make('password'), 'role' => 'escort',
                'email_verified_at' => now(), 'token_balance' => rand(50, 500),
            ]);

            $profile = EscortProfile::create([
                'tenant_id' => $tenant->id, 'user_id' => $user->id,
                'display_name' => $ed['display_name'], 'city' => $ed['city'],
                'neighborhood' => $ed['neighborhood'] ?? null, 'age' => $ed['age'],
                'nationality' => $ed['nationality'], 'languages' => $ed['languages'],
                'description' => $ed['description'], 'services' => $ed['services'],
                'rates' => $ed['rates'], 'contact_phone' => $ed['contact_phone'] ?? null,
                'contact_telegram' => $ed['contact_telegram'] ?? null,
                'contact_email' => $ed['contact_email'] ?? null,
                'is_verified' => $ed['is_verified'],
                'blog_visible_until' => now()->addDays(30),
                'featured_until' => $ed['featured'] ? now()->addDays(7) : null,
                'views_count' => rand(50, 500), 'reviews_count' => rand(3, 20),
                'avg_rating' => round(rand(35, 50) / 10, 1),
            ]);

            $escortUsers[] = ['user' => $user, 'profile' => $profile];
        }

        // Regular users
        $users = [];
        for ($i = 1; $i <= 8; $i++) {
            $users[] = User::create([
                'tenant_id' => $tenant->id, 'username' => "user{$i}", 'name' => "Benutzer {$i}",
                'email' => "user{$i}@example.com", 'password' => Hash::make('password'),
                'role' => 'user', 'email_verified_at' => now(),
            ]);
        }

        // Blog threads + replies
        $comments = ['Toller Blog! Freue mich auf Updates.', 'Sehr schöne Fotos!', 'Wann bist du wieder verfügbar?', 'Tolle Erfahrung letzte Woche!', 'Bitte mehr Fotos!', 'Danke für die schnelle Antwort!'];
        foreach ($escortUsers as $e) {
            $thread = Thread::create([
                'tenant_id' => $tenant->id, 'category_id' => $cats['escort-blogs']->id,
                'user_id' => $e['user']->id, 'escort_profile_id' => $e['profile']->id,
                'title' => $e['profile']->display_name . ' - Mein Blog',
                'body' => $e['profile']->description . "\n\nWillkommen auf meinem Blog! Hier poste ich Updates und Neuigkeiten.",
                'type' => 'blog', 'views_count' => rand(100, 1000), 'replies_count' => rand(5, 30),
                'last_reply_at' => now()->subHours(rand(1, 48)),
            ]);

            for ($j = 0; $j < rand(3, 6); $j++) {
                Post::create([
                    'tenant_id' => $tenant->id, 'thread_id' => $thread->id,
                    'user_id' => $users[array_rand($users)]->id,
                    'body' => $comments[array_rand($comments)],
                ]);
            }
        }

        // Reviews
        $reviewTitles = ['Tolle Erfahrung!', 'Sehr empfehlenswert', 'Wunderschöne Dame', 'Netter Abend', 'Fantastisch!'];
        $reviewBodies = [
            'Eine wunderbare Erfahrung. Pünktlich, freundlich, die Zeit verging wie im Flug. Empfehlenswert!',
            'Sehr nette und gebildete Dame. Das Dinner war toll, die Unterhaltung großartig.',
            'Toller Abend. Sie sieht genau aus wie auf den Fotos. Sehr professionell und diskret.',
            'Gute Erfahrung insgesamt. Pünktlich, freundlich, einfache Kommunikation.',
        ];
        foreach ($escortUsers as $e) {
            for ($k = 0; $k < rand(2, 5); $k++) {
                Review::create([
                    'tenant_id' => $tenant->id, 'user_id' => $users[array_rand($users)]->id,
                    'escort_profile_id' => $e['profile']->id, 'rating' => rand(3, 5),
                    'title' => $reviewTitles[array_rand($reviewTitles)],
                    'body' => $reviewBodies[array_rand($reviewBodies)],
                    'visit_date' => now()->subDays(rand(1, 60)),
                ]);
            }
        }

        // Forum discussions
        foreach ([
            ['title' => 'Willkommen im Forum!', 'body' => 'Herzlich willkommen! Hier könnt ihr Erfahrungen austauschen und mit Escorts in Kontakt treten.', 'pinned' => true],
            ['title' => 'Forenregeln - Bitte lesen!', 'body' => "Regeln:\n1. Respektvoller Umgang\n2. Keine persönlichen Daten Dritter\n3. Keine illegalen Inhalte\n4. Keine Spam-Werbung\n5. Alle Teilnehmer müssen 18+ sein", 'pinned' => true],
            ['title' => 'Beste Gegend in Berlin?', 'body' => 'Bin neu in Berlin und frage mich, welche Gegend am besten für ein Date ist. Tipps?'],
            ['title' => 'Erfahrungen in München', 'body' => 'Wie sind eure Erfahrungen in München? Bin nächste Woche dort.'],
        ] as $disc) {
            $pinned = $disc['pinned'] ?? false;
            Thread::create([
                'tenant_id' => $tenant->id, 'category_id' => $cats['allgemein']->id,
                'user_id' => $pinned ? $admin->id : $users[array_rand($users)]->id,
                'title' => $disc['title'], 'body' => $disc['body'], 'type' => 'discussion',
                'is_pinned' => $pinned, 'views_count' => rand(20, 500),
                'replies_count' => rand(0, 15), 'last_reply_at' => now()->subHours(rand(1, 72)),
            ]);
        }
    }
}
