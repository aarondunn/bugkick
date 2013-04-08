ALTER TABLE bk_bug
ADD CONSTRAINT uc_number UNIQUE (project_id,number);
ALTER TABLE bk_bug
ADD CONSTRAINT uc_prev_number UNIQUE (project_id,prev_number);
ALTER TABLE bk_bug
ADD CONSTRAINT uc_next_number UNIQUE (project_id,next_number);
