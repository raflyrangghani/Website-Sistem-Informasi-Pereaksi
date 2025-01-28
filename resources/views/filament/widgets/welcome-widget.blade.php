<div class="flex items-center p-4 bg-white rounded-lg shadow w-full">
    <div class="flex-shrink-0">
        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
            <span class="text-lg font-bold text-gray-800">{{ auth()->user()->name[0] }}</span>
        </div>
    </div>
    <div class="ml-10">
        <h2 class="text-lg font-semibold">Welcome</h2>
        <p class="text-sm text-gray-600">{{ auth()->user()->name }}</p>
    </div>
</div>