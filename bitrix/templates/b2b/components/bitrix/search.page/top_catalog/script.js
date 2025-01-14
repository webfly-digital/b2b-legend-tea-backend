function JCTitleSearch(arParams) {
    var _this = this;

    this.arParams = {
        'AJAX_PAGE': arParams.AJAX_PAGE,
        'CONTAINER_ID': arParams.CONTAINER_ID,
        'INPUT_ID': arParams.INPUT_ID,
        'MIN_QUERY_LEN': parseInt(arParams.MIN_QUERY_LEN)
    };

    this.cache = [];
    this.cache_key = null;

    this.startText = '';
    this.currentRow = -1;
    this.RESULT = null;
    this.CONTAINER = null;
    this.INPUT = null;
    this.ShowResult = function (result) {
        if (BX.type.isString(result)) {
            _this.RESULT.innerHTML = result;
        }
        if (_this.RESULT.innerHTML !== '') {
            if (_this.RESULT.classList.contains('hidden')) {
                _this.RESULT.classList.remove('hidden')

            }
        } else {
            if (!_this.RESULT.classList.contains('hidden')) {
                _this.RESULT.classList.add('hidden')
            }
        }
    };


    this.onChange = function (callback) {
        if (_this.INPUT.value != _this.oldValue && _this.INPUT.value != _this.startText) {
            _this.oldValue = _this.INPUT.value;
            if (_this.INPUT.value.length >= _this.arParams.MIN_QUERY_LEN) {
                _this.cache_key = _this.arParams.INPUT_ID + '|' + _this.INPUT.value;
                if (_this.cache[_this.cache_key] == null) {

                    BX.ajax.post(
                        _this.arParams.AJAX_PAGE,
                        {
                            'ajax_call': 'y',
                            'INPUT_ID': _this.arParams.INPUT_ID,
                            'q': _this.INPUT.value,
                            'how': 'r',
                        },
                        function (result) {
                            _this.cache[_this.cache_key] = result;
                            _this.ShowResult(result);
                            _this.currentRow = -1;

                            if (!!callback)
                                callback();
                        }
                    );
                    return;
                } else {
                    _this.ShowResult(_this.cache[_this.cache_key]);
                    _this.currentRow = -1;
                }
            } else {
                if (!_this.RESULT.classList.contains('hidden')) _this.RESULT.classList.add('hidden')
                _this.currentRow = -1;
            }

        }
        if (!!callback)
            callback();
    };

    this.onKeyDown = function (e) {
        if (!e) e = window.event;

        if (!_this.RESULT.classList.contains('hidden')) {
            if (e.keyCode == 13) {
                window.location = _this.CONTAINER.action + '?' + _this.INPUT.name+ '=' + _this.INPUT.value;
            }
        }
    };

    this.Init = function () {
        this.CONTAINER = document.getElementById(this.arParams.CONTAINER_ID);
        this.RESULT = this.CONTAINER.appendChild(document.createElement("DIV"));
        this.INPUT = document.getElementById(this.arParams.INPUT_ID);
        this.startText = this.oldValue = this.INPUT.value;
        this.INPUT.onkeydown = this.onKeyDown;

        BX.bind(this.INPUT, 'bxchange', function () {
            _this.onChange()
        });


        BX.bind(this.INPUT, 'click', function () {
            if (_this.INPUT.value == _this.oldValue && _this.RESULT.innerHTML !== '') {
                if (_this.RESULT && _this.RESULT.classList.contains('hidden')) {
                    _this.RESULT.classList.remove('hidden')
                }
            }
        });

        document.addEventListener('click', e => {
            !e.composedPath().includes(_this.RESULT.querySelector(('.search-result'))) && !e.composedPath().includes(_this.INPUT) && !_this.RESULT.classList.contains('hidden') && _this.RESULT.classList.add('hidden')
        })

    };
    BX.ready(function () {
        _this.Init(arParams)
    });
}
