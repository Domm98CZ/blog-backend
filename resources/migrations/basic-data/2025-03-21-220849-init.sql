insert into users (email, password_hash, name, role)
select 'admin@domm.cz', '$2y$12$U/6E0cATy.b81qdiyDc.C.Ipgbi1VInoXCt.mjlBMoPUVdofWBwGq', 'Administrátor', 'admin'::user_role union all
select 'author@domm.cz', '$2y$12$U/6E0cATy.b81qdiyDc.C.Ipgbi1VInoXCt.mjlBMoPUVdofWBwGq', 'Author', 'author'::user_role union all
select 'reader@domm.cz', '$2y$12$U/6E0cATy.b81qdiyDc.C.Ipgbi1VInoXCt.mjlBMoPUVdofWBwGq', 'Čtenář', 'reader'::user_role
where not exists (
    select id from users where email in ('admin@domm.cz', 'author@domm.cz', 'reader@domm.cz')
);
