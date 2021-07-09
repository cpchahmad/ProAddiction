<div class="form-group">
    <div class="col-sm-12">
        <div class="form-material">
            <label for="material-error">City </label>
            <select id="state" name="city" class="form-control">
                @foreach($cities as $city)
                    <option name="state" value="{{$city->name}}">{{$city->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
