crud.field('location_province').onChange(function(field) {
    crud.field('location_community').show(field.value == 1);
    crud.field('location_city').hide(field.value == 1);
}).change();

crud.field('is_separate_building').onChange(function(field) {
    console.log(crud.field('address_apartment'))
    crud.field('address_apartment').show(field.value == 0);
    crud.field('address_apartment').input.value = '';
}).change();


