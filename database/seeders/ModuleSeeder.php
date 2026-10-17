<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Model\Module;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
          [
            'name'=>'Astrologer',
            'key'=>'astrologer',
          ],
          [
            'name'=>'Instructor',
            'key'=>'instructor',
          ],
          [
            'name'=>'Astrologer Review',
            'key'=>'astrologer-review',
          ],
          [
            'name'=>'Instructor Review',
            'key'=>'instructor-review',
          ],
          [
            'name'=>'Astrologer Video Consultation',
            'key'=>'astrologer-video-consultation',
          ],
          [
            'name'=>'Astrologer Chat Consultation',
            'key'=>'astrologer-chat-consultation',
          ],
          [
            'name'=>'Astrologer Call Consultation',
            'key'=>'astrologer-call-consultation',
          ],
          [
            'name'=>'Instructor Video Consultation',
            'key'=>'instructor-video-consultation',
          ],
          [
            'name'=>'Instructor Chat Consultation',
            'key'=>'instructor-chat-consultation',
          ],
          [
            'name'=>'Instructor Call Consultation',
            'key'=>'instructor-call-consultation',
          ],
          [
            'name'=>'Temporary Astrologer Data',
            'key'=>'temporary-astrologer-data',
          ],
          [
            'name'=>'Temporary Instructor Data',
            'key'=>'temporary-instructor-data',
          ],
          [
            'name'=>'Course Category',
            'key'=>'course-category',
          ],
          [
            'name'=>'Course',
            'key'=>'course',
          ],
          [
            'name'=>'Course Review',
            'key'=>'course-review',
          ],
          [
            'name'=>'Product Category',
            'key'=>'product-category',
          ],
          [
            'name'=>'Product',
            'key'=>'product',
          ],
          [
            'name'=>'HSN',
            'key'=>'hsn',
          ],
          [
            'name'=>'Product Review',
            'key'=>'product-review',
          ],
          [
            'name'=>'Banner',
            'key'=>'banner',
          ],
          [
            'name'=>'Advertisement',
            'key'=>'advertisement',
          ],
          [
            'name'=>'User Advertisement',
            'key'=>'astrologer-advertisement',
          ],
          [
            'name'=>'Text Testimonial',
            'key'=>'text-testimonial',
          ],
          [
            'name'=>'Video Testimonial',
            'key'=>'video-testimonial',
          ],
          [
            'name'=>'Blog Category',
            'key'=>'blog-category',
          ],
          [
            'name'=>'Form Field',
            'key'=>'form-field',
          ],
          [
            'name'=>'Coupon',
            'key'=>'coupon',
          ],
          [
            'name'=>'Recharge Plan',
            'key'=>'recharge-plan',
          ],
          [
            'name'=>'Payout Package',
            'key'=>'payout-package',
          ],
          [
            'name'=>'Expertise',
            'key'=>'expertise',
          ],
          [
            'name'=>'NewsLetter',
            'key'=>'newsletter',
          ],
          [
            'name'=>'All Page',
            'key'=>'cms',
          ],
          [
            'name'=>'Contact Us',
            'key'=>'contact-us',
          ],
          [
            'name'=>'Order',
            'key'=>'order',
          ],
          [
            'name'=>'User Recharge',
            'key'=>'user-recharge',
          ],
          [
            'name'=>'Invoice',
            'key'=>'invoice',
          ],
          [
            'name'=>'Language',
            'key'=>'language',
          ],
          [
            'name'=>'User',
            'key'=>'admin',
          ],
          [
            'name'=>'Role',
            'key'=>'role',
          ],
          [
            'name'=>'Module',
            'key'=>'module',
          ],
          [
            'name'=>'Permission',
            'key'=>'permission',
          ],
          [
            'name'=>'Customer',
            'key'=>'customer',
          ],
          [
            'name'=>'CMS Two',
            'key'=>'cms-two',
          ],
          [
            'name'=>'Vastu Page',
            'key'=>'vastu-page',
          ],
          [
            'name'=>'Vastu Service',
            'key'=>'vastu-service',
          ],
          [
            'name'=>'Vastu Procedure',
            'key'=>'vastu-procedure',
          ],
          [
            'name'=>'FAQ',
            'key'=>'faq',
          ],
          [
            'name'=>'Contact Enquiry',
            'key'=>'contact-enquiry',
          ],
          [
            'name'=>'Support',
            'key'=>'support',
          ],
        ];

        Module::where('id', '!=', 0)->delete();
        foreach ($modules as $key => $module) {
          $module = new Module();
          $module->name = $module['name'];
          $module->key = strtolower(str_replace(' ', '-', $module['key']));
          $module->save();
        }
        echo 'done';
    }
}
