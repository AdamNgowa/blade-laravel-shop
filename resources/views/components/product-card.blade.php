@props(['productInCard'])
<div class="bg-white rounded shadow  p-4">
    @if ($productInCard->image)
        <img src="{{ asset('storage/'.$productInCard->image) }}" 
        class="w-full h-48 object-cover rounded">        
    @endif
    <h3 class="text-lg font-bold mt-2">{{$productInCard->title}}</h3>
    <p class="text-sm text-gray-500">{{$productInCard->category->name}}</p>
    <p class="font-semibold text-green-400 mt-3"></p>
</div>