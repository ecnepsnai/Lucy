# Lucy
Lucy is an easy to use and even easier to set up support system that just about anybody can use!

## SQL Table Structure
In the future, Lucy will automatically set up the SQL tables with just a push of a button.  However, until then the tables must be created manually (don't worry, there are only 2 to create).

**userlist**

<table>
	<tr>
		<th>Name</th>
		<th>Type</th>
		<th>Length</th>
		<th>Key</th>
		<th>AI</th>
		<th>Description</th>
	</tr>
	<tr>
		<td>id</td>
		<td>int</td>
		<td>11</td>
		<td>Primary</td>
		<td><strong>Yes</strong></td>
		<td>The ID for each user.</td>
	</tr>
	<tr>
		<td>name</td>
		<td>varchar</td>
		<td>45</td>
		<td></td>
		<td>No</td>
		<td>The name of the user.</td>
	</tr>
	<tr>
		<td>email</td>
		<td>varchar</td>
		<td>45</td>
		<td>Unique</td>
		<td>No</td>
		<td>The email for the user.  Used for authentication</td>
	</tr>
	<tr>
		<td>password</td>
		<td>varchar</td>
		<td>32</td>
		<td></td>
		<td>No</td>
		<td>The encrypted password for the user.</td>
	</tr>
	<tr>
		<td>salt</td>
		<td>varchar</td>
		<td>2</td>
		<td></td>
		<td>No</td>
		<td>The salt used in the encryption process.</td>
	</tr>
	<tr>
		<td>type</td>
		<td>enum</td>
		<td>Admin, Client, Bot, Ban</td>
		<td></td>
		<td>No</td>
		<td>The type of the user</td>
	</tr>
	<tr>
		<td>date_registered</td>
		<td>datetime</td>
		<td></td>
		<td></td>
		<td>No</td>
		<td>When the user was registered</td>
	</tr>
	<tr>
		<td>rig_specs</td>
		<td>TEXT</td>
		<td></td>
		<td></td>
		<td>No</td>
		<td>The user-entered specifications of their computer/device.</td>
	</tr>
</table>

**ticketlist**

<table>
	<tr>
		<th>Name</th>
		<th>Type</th>
		<th>Length</th>
		<th>Key</th>
		<th>Description</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td>id</td>
		<td>int</td>
		<td>11</td>
		<td>Primary</td>
		<td>The ID of the ticket.</td>
	</tr>
	<tr>
		<td>name</td>
		<td>varchar</td>
		<td>45</td>
		<td></td>
		<th>The name of the user who created the ticket</th>
	</tr>
	<tr>
		<td>email</td>
		<td>varchar</td>
		<td>45</td>
		<td></td>
		<th>The email of the user who created the ticket</th>
	</tr>
	<tr>
		<td>application</td>
		<td>enum</td>
		<td>Your application(s)!</td>
		<td></td>
		<th>The application for the ticket.  Used to in new_ticket.php</th>
	</tr>
	<tr>
		<td>version</td>
		<td>varchar</td>
		<td>10</td>
		<td></td>
		<th>The version of the application</th>
	</tr>
	<tr>
		<td>os</td>
		<td>varchar</td>
		<td>20</td>
		<td></td>
		<th>The operating system that the user is using</th>
	</tr>
	<tr>
		<td>status</td>
		<td>enum</td>
		<td>Open, Closed</td>
		<td></td>
		<th>The ticket status</th>
	</tr>
	<tr>
		<td>subject</td>
		<td>varchar</td>
		<td>100</td>
		<td></td>
		<th>A preview of the ticket, used on tickets.php</th>
	</tr>
		<tr>
		<td>date</td>
		<td>datetime</td>
		<td></td>
		<td></td>
		<th>When the ticket was opened.</th>
	</tr>
</table>