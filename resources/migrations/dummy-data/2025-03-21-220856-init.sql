insert into articles (title, content, author_id)
select
    'Technician, Second Class and Third Class'
     , 'Holly recalculated the ship’s course, but only after a three-hour conversation about toast. Starbug’s engines hummed as Lister searched for the last remaining curry in deep space. Meanwhile, Kryten polished a non-existent smudge on a panel that no one had ever looked at before. The Cat adjusted his outfit for the 17th time that morning—because a man has to look good even in a parallel universe. “Engage the bazookoid safety protocols,” Rimmer declared, just as the vending machine refused to dispense his favourite pudding. Time travel paradoxes were nothing compared to the mystery of where Lister’s socks disappeared to. In an alternate dimension, Ace Rimmer was busy saving yet another planet, while on this ship, someone just spilled a cup of tea into the main control panel. Business as usual.'
     , (select id from users where email = 'admin@domm.cz')
where not exists(
    select id from articles where title = 'Technician, Second Class and Third Class' and author_id = (select id from users where email = 'admin@domm.cz')
);

insert into articles (title, content, author_id)
select
    'Lorem ipsum dolor sit amet'
     , 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce gravida magna ante, nec egestas tortor scelerisque at. Quisque est nisi, ultrices sit amet hendrerit in, feugiat nec lacus. Nam eleifend lacus quis velit volutpat consectetur. Curabitur vitae imperdiet sapien. Curabitur sit amet ex tristique erat rhoncus egestas sit amet vitae nibh. Aenean sagittis feugiat scelerisque. Praesent sodales cursus nisi nec viverra. Nullam rutrum neque vitae libero posuere elementum sed vitae leo. Praesent vitae varius dolor. Quisque dictum nisl eu bibendum ullamcorper. Phasellus blandit, justo id egestas auctor, ex nunc dictum enim, semper auctor nunc tellus eget dui.'
     , (select id from users where email = 'author@domm.cz')
where not exists(
    select id from articles where title = 'Lorem ipsum dolor sit amet' and author_id = (select id from users where email = 'author@domm.cz')
);

insert into articles (title, content, author_id)
select
    'Nam non nibh sed quam malesuada rhoncus at ut est'
     , 'Lorem ipsum  sit amet, consectetur adipiscing elit. Fusce gravida magna ante, nec egestas tortor scelerisque at. Quisque est nisi, ultrices sit amet hendrerit in, feugiat nec lacus. Nam eleifend lacus quis velit volutpat consectetur. Curabitur vitae imperdiet sapien. Curabitur sit amet ex tristique erat rhoncus egestas sit amet vitae nibh. Aenean sagittis feugiat scelerisque. Praesent sodales cursus nisi nec viverra. Nullam rutrum neque vitae libero posuere elementum sed vitae leo. Praesent vitae varius dolor. Quisque dictum nisl eu bibendum ullamcorper. Phasellus blandit, justo id egestas auctor, ex nunc dictum enim, semper auctor nunc tellus eget dui.'
     , (select id from users where email = 'author@domm.cz')
where not exists(
    select id from articles where title = 'Nam non nibh sed quam malesuada rhoncus at ut est' and author_id = (select id from users where email = 'author@domm.cz')
);

insert into articles (title, content, author_id)
select
    'Phasellus pharetra vestibulum ex, a dignissim urna pretium at'
    , 'Phasellus pharetra vestibulum ex, a dignissim urna pretium at. Aenean rhoncus, lorem et accumsan cursus, ligula nunc faucibus eros, non mollis nulla sem nec neque. Cras purus velit, tristique in dolor non, elementum aliquet lectus. Sed mollis molestie justo at fringilla. Curabitur feugiat sed mauris at elementum. Nullam tempus tincidunt enim, sit amet tincidunt justo luctus non. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. In pulvinar, orci vel pulvinar mollis, diam erat varius diam, a pretium dolor sem vel velit. Mauris pharetra luctus quam id imperdiet. Nulla ultrices dignissim sem ac commodo. Donec non quam hendrerit, fringilla diam in, porta ipsum. Morbi nec fringilla ligula. Vestibulum vulputate justo nisi, ut fermentum leo tempus et.'
     , (select id from users where email = 'author@domm.cz')
where not exists(
    select id from articles where title = 'Phasellus pharetra vestibulum ex, a dignissim urna pretium at' and author_id = (select id from users where email = 'author@domm.cz')
);

insert into auth_tokens (user_id, token, expires_at)
select
    (select id from users where email = 'admin@domm.cz')
    , 'T35T0V4C1T0K3N'
    , (select current_timestamp + interval '1 hour')
where not exists(
    select id from auth_tokens where user_id = (select id from users where email = 'admin@domm.cz')
);
