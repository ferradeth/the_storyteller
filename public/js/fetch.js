async function postJSON(route, data, _token){
    let response = await fetch(route, {
        method: 'post',
        headers: {
            'X-CSRF-TOKEN': _token,
        },
        body: data,

    })
    return await response.json()
}

async function postDataJSON(route, data, _token){
    let responce = await fetch(route, {
        method: 'post',
        headers: {
            'Content-type': 'application/json;charset=utf-8',
        },
        body: JSON.stringify({
            data,
            _token
        })
    })
    return await responce.json()
}
