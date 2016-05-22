 <?php                   // logging
                    $hist->add(
                        _T("Member card added"),
                        strtoupper($this->_login)
                    );
                    return true;
                } else {
                    $hist->add(_T("Fail to add new member."));
                    throw new \Exception(
                        'An error occured inserting new member!'
                    );
                }
            } else {
                //we're editing an existing member
                if ( !$this->isDueFree() ) {
                    // deadline
                    $due_date = Contribution::getDueDate($this->_id);
                    if ( $due_date ) {
                        $values['date_echeance'] = $due_date;
                    }
                }

                if ( !$this->_password ) {
                    unset($values['mdp_adh']);
                }

                $edit = $zdb->db->update(
                    PREFIX_DB . self::TABLE,
                    $values,
                    self::PK . '=' . $this->_id
                );
                //edit == 0 does not mean there were an error, but that there
                //were nothing to change
                if ( $edit > 0 ) {
                    $this->_updateModificationDate();
                    $hist->add(
                        _T("Member card updated"),
                        strtoupper($this->_login)
                    );
                }
                return true;
            }
            //DEBUG
            return false;
        } catch (\Exception $e) {
            /** FIXME */
            Analog::log(
                'Something went wrong :\'( | ' . $e->getMessage() . "\n" .
                $e->getTraceAsString(),
                Analog::ERROR
            );
            return false;
        }
    }
?>