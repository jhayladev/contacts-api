<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\StoreContactRequest;
use App\Http\Requests\Contact\UpdateContactRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class ContactController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $contacts = $request->user()->contacts()->latest()->paginate(15);

        return ContactResource::collection($contacts);
    }

    public function store(StoreContactRequest $request): ContactResource
    {
        $user = $request->user();

        $contact = $user->contacts()->create([
            ...$request->validated(),
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

        return new ContactResource($contact);
    }

    public function show(Contact $contact): ContactResource
    {
        Gate::authorize('view', $contact);

        return new ContactResource($contact);
    }

    public function update(UpdateContactRequest $request, Contact $contact): ContactResource
    {
        Gate::authorize('update', $contact);

        $contact->update([
            ...$request->validated(),
            'updated_by' => $request->user()->id,
        ]);

        return new ContactResource($contact);
    }

    public function destroy(Request $request, Contact $contact): Response
    {
        Gate::authorize('delete', $contact);

        $contact->update(['deleted_by' => $request->user()->id]);
        $contact->delete();

        return response()->noContent();
    }
}
