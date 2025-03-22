insert into users (email, password_hash, name, role)
select 'admin@domm.cz', '$2y$12$MTWt5hMAGBSmyTkxhDagXefp3cgolPHIEt4w2vO5mUD6RS.RCAKN.', 'Administrátor', 'admin'::user_role union all
select 'author@domm.cz', '$2y$12$MTWt5hMAGBSmyTkxhDagXefp3cgolPHIEt4w2vO5mUD6RS.RCAKN.', 'Author', 'author'::user_role union all
select 'reader@domm.cz', '$2y$12$MTWt5hMAGBSmyTkxhDagXefp3cgolPHIEt4w2vO5mUD6RS.RCAKN.', 'Čtenář', 'reader'::user_role
where not exists (
    select id from users where email in ('admin@domm.cz', 'author@domm.cz', 'reader@domm.cz')
);
