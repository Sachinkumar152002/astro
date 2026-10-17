<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Model\{ApiUrl};
use DB;

class ApiUrlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $apis = [
        [
          "name" => "Kundali PDF - Sampoorna",
          "category" => "vedic",
          "url" => "https://pdf.divineapi.com/indian-api/v2/kundali-sampoorna",
          "request_body_params" => json_encode([
            [
              "name" => "full_name",
              "type" => "text",
              "required" => true,
            ],
            // [
            //   "name" => "day",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "month",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "year",
            //   "type" => "number",
            //   "required" => true,
            // ],
            [
              "name" => "birth_date",
              "type" => "date",
              "required" => true,
            ],
            // [
            //   "name" => "hour",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "min",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "sec",
            //   "type" => "number",
            //   "required" => true,
            // ],
            [
              "name" => "birth_time",
              "type" => "time",
              "required" => true,
            ],
            [
              "name" => "gender",
              "type" => "select",
              "required" => true,
            ],
            [
              "name" => "birth_place",
              "type" => "search",
              "required" => true,
            ],
          ]),
        ],
        [
          "name" => "Kundali PDF - Ananta",
          "category" => "vedic",
          "url" => "https://pdf.divineapi.com/indian-api/v2/kundali-ananta",
          "request_body_params" => json_encode([
            [
              "name" => "full_name",
              "type" => "text",
              "required" => true,
            ],
            // [
            //   "name" => "day",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "month",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "year",
            //   "type" => "number",
            //   "required" => true,
            // ],
            [
              "name" => "birth_date",
              "type" => "date",
              "required" => true,
            ],
            // [
            //   "name" => "hour",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "min",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "sec",
            //   "type" => "number",
            //   "required" => true,
            // ],
            [
              "name" => "birth_time",
              "type" => "time",
              "required" => true,
            ],
            [
              "name" => "gender",
              "type" => "select",
              "required" => true,
            ],
            [
              "name" => "birth_place",
              "type" => "search",
              "required" => true,
            ],
          ]),
        ],
        [
          "name" => "Kundali PDF - Prakash",
          "category" => "vedic",
          "url" => "https://pdf.divineapi.com/indian-api/v2/kundali-prakash",
          "request_body_params" => json_encode([
            [
              "name" => "full_name",
              "type" => "text",
              "required" => true,
            ],
            // [
            //   "name" => "day",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "month",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "year",
            //   "type" => "number",
            //   "required" => true,
            // ],
            [
              "name" => "birth_date",
              "type" => "date",
              "required" => true,
            ],
            // [
            //   "name" => "hour",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "min",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "sec",
            //   "type" => "number",
            //   "required" => true,
            // ],
            [
              "name" => "birth_time",
              "type" => "time",
              "required" => true,
            ],
            [
              "name" => "gender",
              "type" => "select",
              "required" => true,
            ],
            [
              "name" => "birth_place",
              "type" => "search",
              "required" => true,
            ],
          ]),
        ],
        [
          "name" => "Match Making PDF",
          "category" => "vedic",
          "url" => "https://pdf.divineapi.com/indian-api/v2/match-making",
          "request_body_params" => json_encode([
            [
              "name" => "p1_full_name",
              "type" => "text",
              "required" => true,
            ],
            // [
            //   "name" => "p1_day",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "p1_month",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "p1_year",
            //   "type" => "number",
            //   "required" => true,
            // ],
            [
              "name" => "p1_birth_date",
              "type" => "date",
              "required" => true,
            ],
            // [
            //   "name" => "p1_hour",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "p1_min",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "p1_sec",
            //   "type" => "number",
            //   "required" => true,
            // ],
            [
              "name" => "p1_birth_time",
              "type" => "time",
              "required" => true,
            ],
            [
              "name" => "p1_gender",
              "type" => "select",
              "required" => true,
            ],
            [
              "name" => "p1_birth_place",
              "type" => "search",
              "required" => true,
            ],
            [
              "name" => "p2_full_name",
              "type" => "text",
              "required" => true,
            ],
            // [
            //   "name" => "p2_day",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "p2_month",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "p2_year",
            //   "type" => "number",
            //   "required" => true,
            // ],
            [
              "name" => "p2_birth_date",
              "type" => "date",
              "required" => true,
            ],
            // [
            //   "name" => "p2_hour",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "p2_min",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "p2_sec",
            //   "type" => "number",
            //   "required" => true,
            // ],
            [
              "name" => "p2_birth_time",
              "type" => "time",
              "required" => true,
            ],
            [
              "name" => "p2_gender",
              "type" => "select",
              "required" => true,
            ],
            [
              "name" => "p2_birth_place",
              "type" => "search",
              "required" => true,
            ],
          ]),
        ],
        [
          "name" => "Government Job Report",
          "category" => "vedic",
          "url" => "https://pdf.divineapi.com/indian-api/v2/government-job-report",
          "request_body_params" => json_encode([
            [
              "name" => "full_name",
              "type" => "text",
              "required" => true,
            ],
            // [
            //   "name" => "day",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "month",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "year",
            //   "type" => "number",
            //   "required" => true,
            // ],
            [
              "name" => "birth_date",
              "type" => "date",
              "required" => true,
            ],
            // [
            //   "name" => "hour",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "min",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "sec",
            //   "type" => "number",
            //   "required" => true,
            // ],
            [
              "name" => "birth_time",
              "type" => "time",
              "required" => true,
            ],
            [
              "name" => "gender",
              "type" => "select",
              "required" => true,
            ],
            [
              "name" => "birth_place",
              "type" => "search",
              "required" => true,
            ],
          ]),
        ],
        [
          "name" => "Foreign Travel and Settlement",
          "category" => "vedic",
          "url" => "https://pdf.divineapi.com/indian-api/v2/foreign-travel-settlement",
          "request_body_params" => json_encode([
            [
              "name" => "full_name",
              "type" => "text",
              "required" => true,
            ],
            // [
            //   "name" => "day",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "month",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "year",
            //   "type" => "number",
            //   "required" => true,
            // ],
            [
              "name" => "birth_date",
              "type" => "date",
              "required" => true,
            ],
            // [
            //   "name" => "hour",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "min",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "sec",
            //   "type" => "number",
            //   "required" => true,
            // ],
            [
              "name" => "birth_time",
              "type" => "time",
              "required" => true,
            ],
            [
              "name" => "gender",
              "type" => "select",
              "required" => true,
            ],
            [
              "name" => "birth_place",
              "type" => "search",
              "required" => true,
            ],
          ]),
        ],
        [
          "name" => "Vedic Yearly Prediction 5 Year",
          "category" => "vedic",
          "url" => "https://pdf.divineapi.com/indian-api/v2/vedic-yearly-prediction-5-year",
          "request_body_params" => json_encode([
            [
              "name" => "full_name",
              "type" => "text",
              "required" => true,
            ],
            // [
            //   "name" => "day",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "month",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "year",
            //   "type" => "number",
            //   "required" => true,
            // ],
            [
              "name" => "birth_date",
              "type" => "date",
              "required" => true,
            ],
            // [
            //   "name" => "hour",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "min",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "sec",
            //   "type" => "number",
            //   "required" => true,
            // ],
            [
              "name" => "birth_time",
              "type" => "time",
              "required" => true,
            ],
            [
              "name" => "gender",
              "type" => "select",
              "required" => true,
            ],
            [
              "name" => "birth_place",
              "type" => "search",
              "required" => true,
            ],
          ]),
        ],
        [
          "name" => "Vedic Yearly Prediction 10 Year",
          "category" => "vedic",
          "url" => "https://pdf.divineapi.com/indian-api/v2/vedic-yearly-prediction-10-year",
          "request_body_params" => json_encode([
            [
              "name" => "full_name",
              "type" => "text",
              "required" => true,
            ],
            // [
            //   "name" => "day",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "month",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "year",
            //   "type" => "number",
            //   "required" => true,
            // ],
            [
              "name" => "birth_date",
              "type" => "date",
              "required" => true,
            ],
            // [
            //   "name" => "hour",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "min",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "sec",
            //   "type" => "number",
            //   "required" => true,
            // ],
            [
              "name" => "birth_time",
              "type" => "time",
              "required" => true,
            ],
            [
              "name" => "gender",
              "type" => "select",
              "required" => true,
            ],
            [
              "name" => "birth_place",
              "type" => "search",
              "required" => true,
            ],
          ]),
        ],
        [
          "name" => "Vedic Yearly Prediction 15 Year",
          "category" => "vedic",
          "url" => "https://pdf.divineapi.com/indian-api/v2/vedic-yearly-prediction-15-year",
          "request_body_params" => json_encode([
            [
              "name" => "full_name",
              "type" => "text",
              "required" => true,
            ],
            // [
            //   "name" => "day",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "month",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "year",
            //   "type" => "number",
            //   "required" => true,
            // ],
            [
              "name" => "birth_date",
              "type" => "date",
              "required" => true,
            ],
            // [
            //   "name" => "hour",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "min",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "sec",
            //   "type" => "number",
            //   "required" => true,
            // ],
            [
              "name" => "birth_time",
              "type" => "time",
              "required" => true,
            ],
            [
              "name" => "gender",
              "type" => "select",
              "required" => true,
            ],
            [
              "name" => "birth_place",
              "type" => "search",
              "required" => true,
            ],
          ]),
        ],
        [
          "name" => "Natal Report",
          "category" => "natal",
          "url" => "https://pdf.divineapi.com/astrology/v2/report",
          "request_body_params" => json_encode([
            [
              "name" => "full_name",
              "type" => "text",
              "required" => true,
            ],
            // [
            //   "name" => "day",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "month",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "year",
            //   "type" => "number",
            //   "required" => true,
            // ],
            [
              "name" => "birth_date",
              "type" => "date",
              "required" => true,
            ],
            // [
            //   "name" => "hour",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "min",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "sec",
            //   "type" => "number",
            //   "required" => true,
            // ],
            [
              "name" => "birth_time",
              "type" => "time",
              "required" => true,
            ],
            [
              "name" => "gender",
              "type" => "select",
              "required" => true,
            ],
            [
              "name" => "birth_place",
              "type" => "search",
              "required" => true,
            ],
          ]),
        ],
        [
          "name" => "Natal Couple Report",
          "category" => "natal couple",
          "url" => "https://pdf.divineapi.com/astrology/v1/couple",
          "request_body_params" => json_encode([
            [
              "name" => "p1_full_name",
              "type" => "text",
              "required" => true,
            ],
            // [
            //   "name" => "p1_day",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "p1_month",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "p1_year",
            //   "type" => "number",
            //   "required" => true,
            // ],
            [
              "name" => "p1_birth_date",
              "type" => "date",
              "required" => true,
            ],
            // [
            //   "name" => "p1_hour",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "p1_min",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "p1_sec",
            //   "type" => "number",
            //   "required" => true,
            // ],
            [
              "name" => "p1_birth_time",
              "type" => "time",
              "required" => true,
            ],
            [
              "name" => "p1_gender",
              "type" => "select",
              "required" => true,
            ],
            [
              "name" => "p1_birth_place",
              "type" => "search",
              "required" => true,
            ],
            [
              "name" => "p2_full_name",
              "type" => "text",
              "required" => true,
            ],
            // [
            //   "name" => "p2_day",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "p2_month",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "p2_year",
            //   "type" => "number",
            //   "required" => true,
            // ],
            [
              "name" => "p2_birth_date",
              "type" => "date",
              "required" => true,
            ],
            // [
            //   "name" => "p2_hour",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "p2_min",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "p2_sec",
            //   "type" => "number",
            //   "required" => true,
            // ],
            [
              "name" => "p2_birth_time",
              "type" => "time",
              "required" => true,
            ],
            [
              "name" => "p2_gender",
              "type" => "select",
              "required" => true,
            ],
            [
              "name" => "p2_birth_place",
              "type" => "search",
              "required" => true,
            ],
          ]),
        ],
        [
          "name" => "Prediction Report",
          "category" => "numerology prediction",
          "url" => "https://pdf.divineapi.com/numerology/v1/prediction_reports",
          "request_body_params" => json_encode([
            [
              "name" => "full_name",
              "type" => "text",
              "required" => true,
            ],
            // [
            //   "name" => "day",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "month",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "year",
            //   "type" => "number",
            //   "required" => true,
            // ],
            [
              "name" => "birth_date",
              "type" => "date",
              "required" => true,
            ],
            // [
            //   "name" => "hour",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "min",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "sec",
            //   "type" => "number",
            //   "required" => true,
            // ],
            [
              "name" => "birth_time",
              "type" => "time",
              "required" => true,
            ],
            [
              "name" => "gender",
              "type" => "select",
              "required" => true,
            ],
            [
              "name" => "birth_place",
              "type" => "search",
              "required" => true,
            ],
          ]),
        ],
        [
          "name" => "Numerology Report",
          "category" => "numerology",
          "url" => "https://pdf.divineapi.com/numerology/v2/report",
          "request_body_params" => json_encode([
            [
              "name" => "full_name",
              "type" => "text",
              "required" => true,
            ],
            // [
            //   "name" => "day",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "month",
            //   "type" => "number",
            //   "required" => true,
            // ],
            // [
            //   "name" => "year",
            //   "type" => "number",
            //   "required" => true,
            // ],
            [
              "name" => "birth_date",
              "type" => "date",
              "required" => true,
            ],
            [
              "name" => "gender",
              "type" => "select",
              "required" => true,
            ],
          ]),
        ],
      ];

      $natal_report_codes=[
        "CAREER-REPORT"=>"Astro Career Guide",
        "KARMA-LIFE-LESSONS"=>"Karma & Life Lessons",
        "HEALTH-WELL-BEING-GUIDE"=>"Health & Well-being Guide",
        "FINANCIAL-REPORT"=>"Financial Opportunities & Challenges",
        "FRIENDSHIP-SOCIAL-CONNECTIONS"=>"Friendship & Social Connections Insight",
        "FAMILY-ANCESTRAL-TIES"=>"Family & Ancestral Ties Report",
        "EDUCATION-LEARNING-PATHWAYS"=>"Education & Learning Pathways",
        "TRAVEL-RELOCATION-OPPORTUNITIES"=>"Travel & Relocation Opportunities",
        "SPIRITUAL-GROWTH-AWAKENING"=>"Spiritual Growth & Awakening Guide",
        "LIFE-PURPOSE-PASSION"=>"Life Purpose & Passion Exploration",
        "STRESS-COPING-MECHANISMS"=>"Stress & Coping Mechanisms Insight",
        "CREATIVITY-SELF-EXPRESSION"=>"Creativity & Self-expression Guide",
        "LIFE-TRANSITIONS-MILESTONES"=>"Life Transitions & Milestones Forecast",
        "PERSONAL-GROWTH-CHALLENGES"=>"Personal Growth & Challenges",
        "DECISION-MAKING-CHOICES"=>"Decision Making & Choices Guide",
        "EMPOWERMENT-CONFIDENCE-BOOSTERS"=>"Personal Empowerment & Confidence Boosters",
        "ENTREPRENEURSHIP-BUSINESS"=>"Entrepreneurship & Business Ventures Guide",
        "DATING-MATING-RELATING"=>"Dating, Mating & Relating",
        "DIGITAL-INFLUENCE"=>"Digital Presence & Social Media Influence",
      ];
      $natal_couple_report_codes=[
        "COSMIC-CONNECTIONS"=>"Cosmic Connections",
        "ASTROLOVE-INSIGHTS"=>"AstroLove Insights",
        "SOUL-SYNC-REPORT"=>"Soul Sync Report",
        "HARMONIC-HEARTS"=>"Harmonic Hearts",
        "CELESTIAL-UNION-REPORT"=>"Celestial Union Report",
        "THE-COSMIC-LOVE-BLUEPRINT"=>"The Cosmic Love Blueprint",
        "ETERNAL-BOND-REPORT"=>"Eternal Bond Report",
        "ASTROHARMONY-REPORT"=>"AstroHarmony Report",
        "DUAL-DESTINY-REPORT"=>"Dual Destiny Report",
        "SHARED-STARS-REPORT"=>"Shared Stars Report",
        "COSMIC-COMPATIBILITY-CHART"=>"Cosmic Compatibility Chart",
        "TWIN-FLAMES-REPORT"=>"Twin Flames Report",
        "LOVERS’-ASTROLOGY-REPORT"=>"Twin Flames Report",
        "THE-CELESTIAL-BOND"=>"The Celestial Bond",
        "SYNERGY-OF-SOULS-REPORT"=>"The Celestial Bond",
        "ALIGNED-ENERGIES-REPORT"=>"Aligned Energies Report"
      ];
      $numerology_report_codes=[
        "QUEEN-OF-HEARTS"=>"Queen of Hearts: Decoding Your Love Life Numerically",
        "BOSS-LADY-S-BLUEPRINT"=>"Boss Lady’s Blueprint: Mapping Success with Numbers",
        "SCHOLARLY-SPIRITS"=>"Scholarly Spirits: A Female Student's Guide to Numerology",
        "VIRAL-VISIONARY"=>"Viral Visionary: A Female Influencer’s Numerological Map",
        "THE-VISIONARY-S-NUMBERS"=>"The Visionary’s Numbers: Business Success with a Feminine Touch",
        "THE-TECH-DIVA-S-CODE"=>"The Tech Diva’s Code: Numerology for the Female Innovator",
        "ROMANTIC-BEGINNINGS"=>"Romantic Beginnings: A Woman’s Numerology Guide to New Love",
        "TOGETHER-IN-HARMONY"=>"Together in Harmony: A Numerology Guide for Her Lasting Love",
        "FROM-YES-TO-I-DO"=>"From Yes to I Do: A Woman’s Numerology of Engagement",
        "PATH-TO-SELF-LOVE"=>"Path to Self-Love: A Numerological Reinvention Post-Breakup",
        "LONE-WOLFS-PATH"=>"Lone Wolf's Path: Decoding Your Dating Destiny",
        "PROFESSIONAL-PROWESS"=>"Professional Prowess: A Numerological Guide to Climbing the Ladder",
        "CAMPUS-CONQUEROR"=>"Campus Conqueror: The Scholar's Numerological Study",
        "DIGITAL-DOMINANCE"=>"Digital Dominance: A Numerological Approach to Online Influence",
        "STARTUP-SUCCESS"=>"Startup Success: The Young Entrepreneur's Numerology Guide",
        "CODING-YOUR-FUTURE"=>"Coding Your Future: The Innovator's Numerological Compass",
        "NEW-CONNECTION-CODE"=>"New Connection Code: A Man's Guide to Relationship Numerology",
        "COMMITTED-CALCULATIONS"=>"Committed Calculations: The Long-Haul Lover’s Numerical Guide",
        "ENGAGEMENT-EQUATIONS"=>"Engagement Equations: A Numerological Commitment Guide",
        "SOLO-SHIFT"=>"Solo Shift: A Numerology Guide for the New Bachelor",
        "SOLO-SOJOURN"=>"Solo Sojourn: A Woman's Numerological Guide to Independence",
        "CORPORATE-LADDERS-COSMIC-NUMBERS"=>"Corporate Ladders & Cosmic Numbers: The Career Woman's Guide",
        "LOVES-ARITHMETIC"=>"Love's Arithmetic: The Modern Woman's Guide to Romance & Numbers",
        "WHOLENESS-IN-NUMBERS"=>"Wholeness in Numbers: A Numerological Guide to Wellness",
        "MOTHERHOODS-MAGIC-NUMBERS"=>"Motherhood's Magic Numbers: From Bumps to Babies",
        "THE-FRIENDSHIP-FORMULA"=>"The Friendship Formula: Calculating Companionships",
        "FISCAL-FORTUNES"=>"Fiscal Fortunes: A Numerological Approach to Personal Finance",
        "ACADEMIC-ALIGNMENTS"=>"Academic Alignments: A Numerological Study Strategy",
        "THE-ARTISTS-ARITHMETIC"=>"The Artist's Arithmetic: Creative Ventures by the Numbers",
        "SOULFUL-SUMS"=>"Soulful Sums: A Journey Through Spiritual Numerology",
        "CORPORATE-LADDER-CODES"=>"Corporate Ladder Codes: A Numerology Guide for the Up-and-Coming Executive",
        "COMPANIONSHIP-CODE"=>"Companionship Code: Finding Harmony in Numbers",
        "MASTERY-THROUGH-NUMBERS"=>"Mastery Through Numbers: The Graduate’s Numerological Guide",
        "BINARY-BEATS"=>"Binary Beats: The Tech Guru’s Guide to Numerological Success",
        "VENTURE-VIBRATIONS"=>"Venture Vibrations: Tuning Into Your Entrepreneurial Spirit with Numbers",
        "WELLNESS-WRITINGS"=>"Wellness Writings: The Numerology of Health and Fitness",
        "VOYAGER-VALUES"=>"Voyager Values: Numerology for the Globetrotting Man",
        "CULINARY-CODES"=>"Culinary Codes: A Flavorful Take on Numerology",
        "CREATIVE-CURRENTS"=>"Creative Currents: Painting Your Life with Numbers",
        "INNER-INFINITY"=>"Inner Infinity: The Numerology of Spiritual Awakening",
        "EXECUTIVE-ASCENSION"=>"Executive Ascension: Charting Your Mid-Career Numerology",
        "RENEWED-ROMANCE"=>"Renewed Romance: A Numerological Guide to Recharging Relationships",
        "NUMEROLOGY-FOR-LIFELONG-VITALITY-HEALTH-GUIDE"=>"Numerology for Lifelong Vitality: A Man's Health Guide",
        "THE-ENTREPRENEURS-NUMEROLOGICAL-NAVIGATOR"=>"The Entrepreneur's Numerological Navigator: Ages 35-45",
        "WEALTH-IN-NUMBERS"=>"Wealth in Numbers: A 35-45 Man's Guide to Financial Numerology",
        "LEARNING-S-NEW-LONGITUDE"=>"Learning’s New Longitude: Numerology for the Lifelong Learner",
        "NUMEROLOGICAL-REBIRTH"=>"Numerological Rebirth: Transforming at 35-45",
        "GLOBETROTTER-S-GUIDE-TO-NUMEROLOGY"=>"Globetrotter’s Guide to Numerology: Discovering Destinations in Your Prime",
        "THE-GOURMETS-NUMEROLOGICAL-GUIDE"=>"The Gourmet's Numerological Guide: Cooking with Numbers",
        "HARMONY-IN-NUMBERS"=>"Harmony in Numbers: A Musician's Guide to Numerological Creativity",
        "NUMEROLOGY-OF-COMMAND"=>"Numerology of Command: Leading with Numbers",
        "MIDLIFE-NUMEROLOGY"=>"Midlife Numerology: Numbers for Navigating Change",
        "PRIME-OF-LIFE"=>"Prime of Life: Numerological Health for the Mature Man",
        "NUMEROLOGICAL-INHERITANCE"=>"Numerological Inheritance: Building a Legacy",
        "GUIDING-NUMBERS"=>"Guiding Numbers: Mentoring with Numerology",
        "NUMEROLOGY-FOR-FATHERS"=>"Numerology for Fathers: Guiding the Next Generation",
        "SOULFUL-SUMMATIONS"=>"Soulful Summations: Spiritual Insights for the Mature Man",
        "EXPLORER-S-EPITOME"=>"Explorer’s Epitome: Numerology for Global Adventures",
        "CREATIVE-CONSTRUCTS"=>"Creative Constructs: Numerology for Artistic Expression",
        "NUMEROLOGY-FOR-LIFELONG-LEARNING"=>"Numerology for Lifelong Learning: The Mature Mind’s Map",
        "MIDLIFE-RENAISSANCE"=>"Midlife Renaissance: A Numerological Rebirth",
        "VITALITY-BY-THE-NUMBERS"=>"Vitality by the Numbers: Thriving After 45",
        "LEADING-WITH-NUMBERS"=>"Leading with Numbers: A Guide for the Seasoned Woman",
        "PROSPERITY-PATHWAYS"=>"Prosperity Pathways: Numerology for Financial Health",
        "BONDING-BY-THE-NUMBERS"=>"Bonding by the Numbers: Love in the Wisdom Years",
        "ARTISTIC-ALGORITHMS"=>"Artistic Algorithms: Numerology for the Creative Mind",
        "SOUL-NUMBERS"=>"Soul Numbers: A Journey to Spiritual Awakening",
        "ADVENTURER-S-ARITHMETIC"=>"Adventurer’s Arithmetic: Discoveries after 45",
        "NUMEROLOGY-FOR-THE-NURTURING-HEART"=>"Numerology for the Nurturing Heart: Family Legacies",
        "LIFELONG-LEARNER"=>"Lifelong Learner: Numerology for Personal Enrichment",
        "NUMEROLOGY-FOR-GOLDEN-YEARS-PLANNING"=>"Numerology for Golden Years Planning",
        "LEGACY-LINES"=>"Legacy Lines: Crafting Your Story with Numbers",
        "NUMEROLOGY-FOR-LIFELONG-VITALITY"=>"Numerology for Lifelong Vitality",
        "SENIOR-ADVENTURES"=>"Senior Adventures: Numerology for the Worldly Man",
        "NUMEROLOGY-FOR-THE-SOULFUL-SENIOR"=>"Numerology for the Soulful Senior",
        "NUMEROLOGY-FOR-THE-HOBBYIST"=>"Numerology for the Hobbyist: Fine-Tuning Fun",
        "NUMEROLOGY-FOR-THE-MENTOR"=>"Numerology for the Mentor: Sharing Life’s Lessons",
        "NUMEROLOGY-FOR-GRANDFATHERS"=>"Numerology for Grandfathers: The Joy of Generations",
        "FULFILLMENT-AT-FIFTY-FIVE-PLUS"=>"Fulfillment at Fifty-Five Plus: Numerology's Role",
        "NUMEROLOGY-FOR-COMMUNITY-LEADERS"=>"Numerology for Community Leaders",
        "NUMEROLOGY-FOR-THE-SECOND-SPRING"=>"Numerology for the Second Spring",
        "NUMEROLOGY-FOR-RADIANT-HEALTH"=>"Numerology for Radiant Health",
        "A-TAPESTRY-OF-TIME"=>"A Tapestry of Time: Weaving Your Legacy",
        "THE-SEASONED-SOJOURNER"=>"The Seasoned Sojourner: Numerology for the Traveller",
        "SOULFUL-NUMBERS"=>"Soulful Numbers: Numerology for Spiritual Depth",
        "CREATIVE-NUMEROLOGY-FOR-THE-ARTISTIC-SOUL"=>"Creative Numerology for the Artistic Soul",
        "THE-GRANDMOTHERS-NUMEROLOGY"=>"The Grandmother's Numerology: Bonds & Blessings",
        "COMMUNITY-THREADS"=>"Community Threads: Numerology for Social Good",
        "LIFELONG-LEARNING"=>"Lifelong Learning: Numerology for Knowledge Seekers",
        "GOLDEN-GRACE"=>"Golden Grace: Numerology for Self-Care",
        "CAREER-SUCCESS"=>"Career Success: Numerology for Midlife Women",
        "LOVE-RENEWED"=>"Numerology of the Heart: Rediscovering Love and Connection",
        "WELLNESS-HARMONY"=>"Wellness Harmony: Numerology for Women",
        "BUSINESS-BRILLIANCE"=>"Business Brilliance: Numerology for Women",
        "FINANCIAL-WISDOM"=>"Financial Wisdom: Numerology for Women",
        "NUMEROLOGY-LIFELONG-LEARNING"=>"Lifelong Learning: Numerology for Women",
        "REINVENTING-LIFE"=>"Reinventing Life: Numerology for Women",
        "TRAVELERS-NUMEROLOGY"=>"Traveler's Numerology: Exploring the World by Numbers",
        "CULINARY-NUMEROLOGY"=>"Culinary Numerology: A Woman's Guide",
        "ARTISTIC-INSPIRATION"=>"Artistic Inspiration: Numerology for Women",
        "SOULPRINT-MINI"=>"Soulprint Mini",
        "SOULPRINT-BASIC"=>"Soulprint Basic",
        "SOULPRINT-PRO"=>"Soulprint Pro",
      ];
      $numerology_prediction_report_codes=[
        "YEARLY-PREDICTION-3-YEAR"=>"Yearly Prediction 3 Year",
        "YEARLY-PREDICTION-5-YEAR"=>"Yearly Prediction 5 Year",
        "YEARLY-PREDICTION-9-YEAR"=>"Yearly Prediction 9 Year",
      ];
      DB::select("truncate table api_urls");
      foreach($apis as $index => $item){
        $name = $item["name"];
        $key = str_slug($name);
        $category = $item["category"];
        $url = $item["url"];
        $request_body_params = $item["request_body_params"];
        if($name=="Natal Report"){
          foreach($natal_report_codes as $indexSecond=>$itemSecond){
            $name=$item["name"];
            $name="$name - $itemSecond";
            $key=str_slug($name);
            $report_code=$indexSecond;
            $this->insertUpdateApiUrl($name,$key,$category,$url,$request_body_params,$report_code);
          }
        }elseif($name=="Natal Couple Report"){
          foreach($natal_couple_report_codes as $indexSecond=>$itemSecond){
            $name=$item["name"];
            $name="$name - $itemSecond";
            $key=str_slug($name);
            $report_code=$indexSecond;
            $this->insertUpdateApiUrl($name,$key,$category,$url,$request_body_params,$report_code);
          }
        }elseif($name=="Prediction Report"){
          foreach($numerology_prediction_report_codes as $indexSecond=>$itemSecond){
            $name=$item["name"];
            $name="$name - $itemSecond";
            $key=str_slug($name);
            $report_code=$indexSecond;
            $this->insertUpdateApiUrl($name,$key,$category,$url,$request_body_params,$report_code);
          }
        }elseif($name=="Numerology Report"){
          foreach($numerology_report_codes as $indexSecond=>$itemSecond){
            $name=$item["name"];
            $name="$name - $itemSecond";
            $key=str_slug($name);
            $report_code=$indexSecond;
            $this->insertUpdateApiUrl($name,$key,$category,$url,$request_body_params,$report_code);
          }
        }else{
          $this->insertUpdateApiUrl($name,$key,$category,$url,$request_body_params,"");
        }
      }
      echo "done";
    }

    public function insertUpdateApiUrl($name,$key,$category,$url,$request_body_params,$report_code){
      $apiUrl=ApiUrl::where("key", $key)->first();
      if($apiUrl==null){
        $apiUrl=new ApiUrl();
        $apiUrl->name=$name;
        $apiUrl->key=$key;
      }
      $apiUrl->category=$category;
      $apiUrl->url=$url;
      $apiUrl->request_body_params=$request_body_params;
      $apiUrl->report_code=$report_code;
      $apiUrl->save();
    }


}
