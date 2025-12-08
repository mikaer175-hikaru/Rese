<div class="p-form__group">
    <label for="name" class="p-form__label">店舗名</label>
    <input id="name" type="text" name="name" class="p-form__input"
           value="{{ old('name', $shop->name ?? '') }}">
    @error('name')
        <p class="p-form__error">{{ $message }}</p>
    @enderror
</div>

<div class="p-form__group">
    <label for="description" class="p-form__label">店舗説明</label>
    <textarea id="description" name="description" class="p-form__textarea">{{ old('description', $shop->description ?? '') }}</textarea>
    @error('description')
        <p class="p-form__error">{{ $message }}</p>
    @enderror
</div>

<div class="p-form__group">
    <label for="area_id" class="p-form__label">エリア</label>
    <select id="area_id" name="area_id" class="p-form__select">
        <option value="">選択してください</option>
        @foreach ($areas as $area)
            <option value="{{ $area->id }}"
                @selected(old('area_id', $shop->area_id ?? '') == $area->id)>
                {{ $area->name }}
            </option>
        @endforeach
    </select>
    @error('area_id')
        <p class="p-form__error">{{ $message }}</p>
    @enderror
</div>

<div class="p-form__group">
    <label for="genre_id" class="p-form__label">ジャンル</label>
    <select id="genre_id" name="genre_id" class="p-form__select">
        <option value="">選択してください</option>
        @foreach ($genres as $genre)
            <option value="{{ $genre->id }}"
                @selected(old('genre_id', $shop->genre_id ?? '') == $genre->id)>
                {{ $genre->name }}
            </option>
        @endforeach
    </select>
    @error('genre_id')
        <p class="p-form__error">{{ $message }}</p>
    @enderror
</div>

<div class="p-form__group">
    <label for="image" class="p-form__label">店舗画像</label>
    <input id="image" type="file" name="image" class="p-form__input">
    @error('image')
        <p class="p-form__error">{{ $message }}</p>
    @enderror

    @isset($shop)
        @if ($shop->image_path)
            <p class="p-form__note">現在の画像：</p>
            <img src="{{ asset('storage/' . $shop->image_path) }}"
                 alt="{{ $shop->name }}"
                 class="p-form__image-preview">
        @endif
    @endisset
</div>
