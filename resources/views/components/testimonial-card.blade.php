@props(['testimonial'])

<article class="testimonial-card card">
    <p>“{{ $testimonial->localized_content }}”</p>
    <h3>{{ $testimonial->client_name }}</h3>
    @if($testimonial->localized_title)
        <p class="muted">{{ $testimonial->localized_title }}</p>
    @endif
</article>
