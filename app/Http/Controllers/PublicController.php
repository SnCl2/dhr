<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\SiteContent;
use App\Models\Bulletin;
use App\Models\Employee;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Get a CMS value by key.
     */
    private function getCmsValue($key, $default = '')
    {
        return SiteContent::where('key', $key)->value('value') ?? $default;
    }

    /**
     * Display the home page.
     */
    public function home()
    {
        $cms = [
            'banner_title' => $this->getCmsValue('home_banner_title', 'Elite Manpower Hiring & Staffing Solutions'),
            'banner_subtitle' => $this->getCmsValue('home_banner_subtitle', 'Empowering businesses with top-tier talent. Providing workers with seamless career opportunities.'),
            'about_text' => $this->getCmsValue('about_us_text', 'We are a premier manpower supplier, staffing agency, and recruitment consulting firm.'),
        ];

        // Fetch some dynamic stats
        $stats = [
            'total_employees' => Employee::where('status', 'active')->count() + 140, // baseline + dynamic
            'active_placements' => Employee::where('status', 'active')->count() + 85,
            'partners' => 12,
        ];

        return view('home', compact('cms', 'stats'));
    }

    /**
     * Display the about us page.
     */
    public function about()
    {
        $cms = [
            'about_text' => $this->getCmsValue('about_us_text', 'We are a premier manpower supplier, staffing agency, and recruitment consulting firm with over 10 years of experience.'),
        ];

        return view('about', compact('cms'));
    }

    /**
     * Display the services page.
     */
    public function services()
    {
        return view('services');
    }

    /**
     * Display the gallery page.
     */
    public function gallery()
    {
        // Sample gallery items (can be customized or dynamically uploaded)
        $images = [
            ['title' => 'Warehouse Staff Operations', 'category' => 'Logistics', 'url' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=600&q=80'],
            ['title' => 'Onboarding Training Session', 'category' => 'Corporate', 'url' => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=600&q=80'],
            ['title' => 'Office Staffing Placement', 'category' => 'Administration', 'url' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=600&q=80'],
            ['title' => 'Industrial Assembly Line', 'category' => 'Manufacturing', 'url' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=600&q=80'],
            ['title' => 'Tech Team Collaboration', 'category' => 'IT Staffing', 'url' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=600&q=80'],
            ['title' => 'Recruitment Interview Panel', 'category' => 'Hiring', 'url' => 'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?auto=format&fit=crop&w=600&q=80'],
        ];

        return view('gallery', compact('images'));
    }

    /**
     * Display the contact us page.
     */
    public function contact()
    {
        $cms = [
            'email' => $this->getCmsValue('contact_email', 'info@propszy.com'),
            'phone' => $this->getCmsValue('contact_phone', '+91 94323 13430'),
            'address' => $this->getCmsValue('contact_address', 'Amtala, DH Road, South 24 Parganas, West Bengal, 743503'),
        ];

        return view('contact', compact('cms'));
    }

    /**
     * Display the director's desk page.
     */
    public function directorsDesk()
    {
        return view('directors-desk');
    }

    /**
     * Handle contact form submission.
     */
    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        Inquiry::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'unread',
        ]);

        return back()->with('success', 'Your inquiry has been submitted successfully. Our team will contact you shortly.');
    }
}
