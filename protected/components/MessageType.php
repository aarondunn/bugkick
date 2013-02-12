<?php
/**
 *
 * @author f0t0n
 */
interface MessageType {

	const NEW_TICKET = 0;
	const TICKET_CHANGED = 1;
	const TICKET_DEADLINE_REACHED = 2;
	const TICKETS_ORDER_CHANGED = 3;
	const NEW_COMMENT = 4;
}