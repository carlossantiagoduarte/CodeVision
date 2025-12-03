<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $events = Event::with('categories')->latest()->paginate(12);
        return view('events.index', compact('events'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('events.create', compact('categories'));
    }

    public function store(StoreEventRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id() ?? 1;

        if ($request->hasFile('banner')) {
            $data['banner_url'] = $request->file('banner')->store('event_banners', 'public');
        }

        $event = Event::create($data);

        if (!empty($data['categories'])) {
            $event->categories()->sync($data['categories']);
        }

        return redirect()->route('events.show', $event)
            ->with('success', 'Evento creado correctamente.');
    }

    public function show(Event $event)
    {
        $event->load('categories','organizerUser','teams');
        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $categories = Category::all();
        return view('events.edit', compact('event','categories'));
    }

    public function update(StoreEventRequest $request, Event $event)
    {
        $data = $request->validated();

        if ($request->hasFile('banner')) {

            if ($event->banner_url && Storage::disk('public')->exists($event->banner_url)) {
                Storage::disk('public')->delete($event->banner_url);
            }

            $data['banner_url'] = $request->file('banner')->store('event_banners', 'public');
        }

        $event->update($data);

        if (!empty($data['categories'])) {
            $event->categories()->sync($data['categories']);
        }

        return redirect()->route('events.show', $event)
            ->with('success','Evento actualizado.');
    }

    public function destroy(Event $event)
    {
        if ($event->banner_url && Storage::disk('public')->exists($event->banner_url)) {
            Storage::disk('public')->delete($event->banner_url);
        }

        $event->delete();

        return redirect()->route('events.index')
            ->with('success','Evento eliminado.');
    }
}
