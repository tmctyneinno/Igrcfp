<?php

namespace Database\Seeders;

use App\Models\Chapter;
use App\Models\ChapterLeadership;
use Illuminate\Database\Seeder;

class ChapterSeeder extends Seeder
{
    public function run(): void
    {
        $chapters = [
            [
                'region' => 'West Africa',
                'slug' => 'west-africa',
                'country_focus' => 'Nigeria, Ghana, Senegal, Sierra Leone, Liberia',
                'description' => 'The IGRCFP West Africa Chapter serves governance, risk, compliance, and financial crime professionals across the region. We provide local regulatory insights, networking, and professional development aligned with international standards.',
                'annual_fee' => 0.00,
                'contact_email' => 'westafrica@igrcfp.org',
                'meeting_frequency' => 'Quarterly meetings + monthly webinars',
                'benefits' => [
                    'Region-specific regulatory updates and alerts',
                    'Access to local networking events and roundtables',
                    'Earn IGRCFP CPD/CPE credits for all events',
                    'Exclusive workshops and training sessions',
                    'Opportunity to engage with regulators and industry leaders',
                    'Access to local job and career opportunities',
                    'Discounted rates for regional conferences',
                    'Volunteer and leadership development opportunities'
                ],
                'is_active' => true
            ],
            [
                'region' => 'East Africa',
                'slug' => 'east-africa',
                'country_focus' => 'Kenya, Uganda, Tanzania, Rwanda, Ethiopia',
                'description' => 'The IGRCFP East Africa Chapter connects professionals working in risk, compliance, and financial crime prevention across East Africa. We support members with local market knowledge and global best practices.',
                'annual_fee' => 0.00,
                'contact_email' => 'eastafrica@igrcfp.org',
                'meeting_frequency' => 'Bi-monthly meetings + quarterly webinars',
                'benefits' => [
                    'Local regulatory updates and compliance guidance',
                    'Networking with peers and industry experts',
                    'CPD accredited events and training',
                    'Access to exclusive industry reports',
                    'Career development support',
                    'Discounts on national and regional events'
                ],
                'is_active' => true
            ],
            [
                'region' => 'Southern Africa',
                'slug' => 'southern-africa',
                'country_focus' => 'South Africa, Botswana, Namibia, Zambia, Zimbabwe',
                'description' => 'The IGRCFP Southern Africa Chapter brings together professionals in governance, risk, and compliance across the Southern African region. We focus on regional regulatory frameworks and professional growth.',
                'annual_fee' => 0.00,
                'contact_email' => 'southernafrica@igrcfp.org',
                'meeting_frequency' => 'Quarterly',
                'benefits' => [
                    'Regional regulatory updates',
                    'Networking events and forums',
                    'CPD accredited activities',
                    'Access to technical resources',
                    'Mentorship opportunities'
                ],
                'is_active' => true
            ],
            [
                'region' => 'Europe',
                'slug' => 'europe',
                'country_focus' => 'United Kingdom, Ireland, Germany, France, Netherlands',
                'description' => 'The IGRCFP Europe Chapter serves members across the European Union and UK. We focus on EU regulations, UK frameworks, and cross-border compliance issues.',
                'annual_fee' => 25.00,
                'contact_email' => 'europe@igrcfp.org',
                'meeting_frequency' => 'Monthly webinars + annual conference',
                'benefits' => [
                    'EU and UK regulatory updates',
                    'Cross-border compliance insights',
                    'Access to European events and conferences',
                    'International networking opportunities',
                    'CPD credits'
                ],
                'is_active' => true
            ]
        ];

        foreach ($chapters as $chapterData) {
            $chapter = Chapter::updateOrCreate(
                ['slug' => $chapterData['slug']], // Find by unique slug
                $chapterData // Update if exists, create if new
            );

            // Add sample leadership members
            ChapterLeadership::create([
                'chapter_id' => $chapter->id,
                'name' => 'Dr. Samuel Okoro',
                'role' => 'Chapter President',
                'email' => $chapter->contact_email,
            ]);

            ChapterLeadership::create([
                'chapter_id' => $chapter->id,
                'name' => 'Ms. Amara Bello',
                'role' => 'Chapter Secretary',
                'email' => $chapter->contact_email,
            ]);
        }
    }
}