# cyberark-ansible-lamp

This is an example of how to deploy a LAMP Stack using Ansible Tower secured bi-directionally with CyberArk PAS Core & Application Identity Manager (AIM) and, separately, CyberArk Conjur.

## Technical Summary

Tested on RHEL 7.5 VM remote from RHEL 7.5 Ansible Tower Controller.

### CyberArk PAS Core + AIM

Using secrets stored and automatically managed in CyberArk EPV, we are able to fetch the secrets when needed into a playbook running in a Job Template within Ansible Tower.

[![CyberArk & Ansible - AIM Bi-Directional Integration](https://img.youtube.com/vi/PHT76FYLNbY/0.jpg)](https://www.youtube.com/watch?v=PHT76FYLNbY)

#### Roles Needed

`$ ansible-galaxy install cyberark.modules`

#### Setup in CyberArk EPV

1. Create an Application ID for Ansible.
2. Create a safe for storing your Private Keys for access to hosts.
3. Create a safe for storing your REST API account for Ansible to use.
4. Create a safe for storing your MySQL database user created during deployment.
5. Permission your Ansible Application ID, AIMWebService for AIM CCP, and PROV_ provider user to each safe.  Be sure to grant just default permissions for each safe except where the MySQL database user will be onboarded.  That should include additional permissions on the Ansible Application ID to allow adding accounts and updating properties.
6. Create an Application ID for your PHP Webapp.
7. Permission your PHP Webapp Application ID to the MySQL database user safe with default permissions for later fetching via AIM CCP.
8. In Platform Management for the MySQL platform that will be used, optionally enable "AutoChangeOnAdd" in the "UI & Workflows" general section.  This will change the password of the MySQL User Object upon onboarding to the safe.

#### Setup in Ansible Tower

1. Create a Project pointing to this repository.
2. Create a Job Template targeting the Inventory to deploy the LAMP Stack.  Use the `aim.yml` playbook.  Add a `Dummy Credential` with no user/pass to the Job Template since it's a requirement.
3. Make sure the values in `aim.yml` match your environment.
4. Launch the Job!

#### Setup in CyberArk Conjur

1. In the `./policies/` directory are the policies to be loaded into CyberArk Conjur.
2. `conjur policy load root root.yml`
3. `conjur policy load ansible-tower ansible-tower.yml`
4. Add the Host ID and API Key to a custom credential type for CyberArk Conjur Machine Identity. (This will be provided later here)
5. `conjur policy load helloworldphp helloworldphp.yml`
6. After loading the policies, login to the Conjur UI and intialize each secret created with a value.  For the Private Key secret that is created, copy the contents of the Private Key from EPV into the Secret Manager.

#### Setup in Ansible Tower

1. Create a Project pointing to this repository.
2. Create a Job Template targeting the Inventory to deploy the LAMP Stack.  Use the `conjur.yml` playbook.  Add the `CyberArk Conjur Machine Identity` credential to the Job Template.
3. Make sure the values in `conjur.yml` match your environment.
4. Launch the Job!

# License

MIT
