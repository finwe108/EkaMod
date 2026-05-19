@if ($errors->any())
    <div class="card" style="margin-bottom: 18px; border-color: rgba(240,96,96,.35);">
        <div class="card-header">
            <div>
                <div class="card-title">Please review the form</div>
                <div class="card-subtitle">Some fields have errors and need correction</div>
            </div>
        </div>
        <div class="card-body">
            <ul style="margin: 0; padding-left: 18px; color: var(--red); line-height: 1.7;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif