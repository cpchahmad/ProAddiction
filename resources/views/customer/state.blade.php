<div class="form-group">
    <div class="col-sm-12">
        <div class="form-material">
            <label for="material-error">State </label>
            <select id="state" name="state" class="form-control" onchange="showcities(this.value);">
                @foreach($states as $state)
                    <option name="state" value="{{$state->name}}">{{$state->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
