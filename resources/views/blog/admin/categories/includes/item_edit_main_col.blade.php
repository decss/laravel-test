<div class="row justify-content-center">
    <div class="col col-md-12">
        <div class="card">
            <div class="card-body">
                {{--<div class="card-title"></div>--}}
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a href="#maindata" class="nav-link active" data-toggle="tab" role="tab">Основные данные</a>
                    </li>
                </ul>
                <br>
                <div class="tab-content">
                    <div class="tab-pane active" id="maindata" role="tabpanel">
                        <div class="form-group">
                            <label for="title">Заголовок</label>
                            <input type="text" name="title" value="{{ $item->title }}"
                                   id="title"
                                   class="form-control"
                                   minlength="3"
                                   required>
                        </div>
                        <div class="form-group">
                            <label for="slug">Идентификатор</label>
                            <input type="text" name="slug" value="{{ $item->slug }}"
                                   id="slug"
                                   class="form-control"
                                   minlength="3">
                        </div>
                        <div class="form-group">
                            <label for="parent_id">Идентификатор</label>
                            <select name="parent_id" id="parent_id" class="form-control">
                                @foreach($categoryList as $option)
                                    <option value="{{ $option->id }}" @if($option->id == $item->parent_id) selected @endif>
                                        {{ $option->id }}. {{ $option->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="description">Идентификатор</label>
                            <textarea name="description" id="description" class="form-control" rows="5">{{
                                old('description', $item->description)
                            }}</textarea>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
