<div class="mt-8 border-t pt-6">
    <h2 class="text-xl font-bold mb-4">Organization Details</h2>
    
    @if($user->organization_logo)
    <div class="mb-4">
        <img src="{{ asset('storage/'.$user->organization_logo) }}" 
             class="w-32 h-32 object-contain">
    </div>
    @endif

    <div class="space-y-2">
        @if($user->organization_name)
        <p><span class="font-semibold">Organization:</span> {{ $user->organization_name }}</p>
        @endif
        
        <p><span class="font-semibold">Plan:</span> 
            <span class="capitalize px-2 py-1 bg-blue-100 text-blue-800 rounded">
                {{ $user->subscription_plan }}
            </span>
        </p>
        
        @if($user->validity)
        <p><span class="font-semibold">Valid Until:</span> 
            {{ \Carbon\Carbon::parse($user->validity)->format('M d, Y') }}
        </p>
        @endif
    </div>
</div>