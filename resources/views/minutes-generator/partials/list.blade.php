@forelse ($items as $item)
    <ul>
        <li>{{ $item }}</li>
    </ul>
@empty
    <p>No identificado</p>
@endforelse
