insert into users (email, password_hash, name, role)
select 'admin@domm.cz', 'xyz', 'Administrátor', 'admin'::user_role union all
select 'author@domm.cz', 'xyz', 'Author', 'author'::user_role union all
select 'reader@domm.cz', 'xyz', 'Čtenář', 'reader'::user_role
where not exists (
    select id from users where email in ('admin@domm.cz', 'author@domm.cz', 'reader@domm.cz')
);
