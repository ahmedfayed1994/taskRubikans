<select name="model" id="model" class="form-select">
    @foreach($items as $item)
        <option value="{{$item->id}}">{{$item->name}}</option>
    @endforeach
</select>
