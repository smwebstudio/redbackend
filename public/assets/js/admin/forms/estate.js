crud.field('location_province').onChange(function(field) {
    crud.field('location_community').show(field.value == 1);
    crud.field('location_city').hide(field.value == 1);
}).change();

crud.field('is_separate_building').onChange(function(field) {
    crud.field('address_apartment').show(field.value == 0);
    crud.field('address_apartment').input.value = '';
}).change();

crud.field('is_estate_commercial_land').onChange(function(field) {
    crud.field('land_structure_type').show(field.value == 1).enable(field.value == 1);
    crud.field('communication_type').show(field.value == 1).enable(field.value == 1);
    crud.field('fence_type').show(field.value == 1).enable(field.value == 1);
    crud.field('road_way_type').show(field.value == 1).enable(field.value == 1);
    crud.field('front_with_street').show(field.value == 1).enable(field.value == 1);
    crud.field('front_length').show(field.value == 1).enable(field.value == 1);
}).change();

crud.field('is_organization').onChange(function(field) {
    crud.field('name_arm').show(field.value == 0).enable(field.value == 0);
    crud.field('name_en').show(field.value == 0).enable(field.value == 0);
    crud.field('name_ru').show(field.value == 0).enable(field.value == 0);
    crud.field('last_name_arm').show(field.value == 0).enable(field.value == 0);
    crud.field('last_name_en').show(field.value == 0).enable(field.value == 0);
    crud.field('last_name_ru').show(field.value == 0).enable(field.value == 0);
    crud.field('organization').show(field.value == 1).enable(field.value == 1);
}).change();

crud.field('estate_status').onChange(function(field) {
    crud.field('archive_till_date').show(field.value == 8).enable(field.value == 8);
    crud.field('archive_comment_arm').show(field.value == 8).enable(field.value == 8);
}).change();


