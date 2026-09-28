@props(['placement' => 'feed'])
<div class="ad {{ $placement }}" role="complementary" aria-label="Advertisement placement"><div>Advertisement<small>Reserved placement · {{ $placement === 'side' ? '300 × 250' : 'Responsive' }}</small></div></div>
