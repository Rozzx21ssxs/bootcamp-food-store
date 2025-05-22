<input type="button" value="-" wire:click="decrement({{ $cart_id, $product_id }})" @if($disabled <= 1) disabled @endif class="button-minus border-0 rounded-circle icon-shape icon-sm mx-1" data-field="quantity">


