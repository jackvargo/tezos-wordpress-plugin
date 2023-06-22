// Import the required libraries
import { TezosToolkit } from '@taquito/taquito';
import { BeaconWallet } from '@taquito/beacon-wallet';
import { OpKind } from '@taquito/taquito';



// Initialize the Tezos toolkit and Beacon wallet
const Tezos = new TezosToolkit("https://mainnet.api.tez.ie");
const wallet = new BeaconWallet({ name: "Tezos Wordpress Plugin" });
Tezos.setWalletProvider(wallet);

async function interactWithSmartContract(contractAddress, entrypoint, parameters) {
  try {
    const contract = await Tezos.wallet.at(contractAddress);
    const op = await contract.methods[entrypoint](...parameters).send();
    await op.confirmation();
    console.log("Transaction successful!");
  } catch (error) {
    console.error("Error interacting with the smart contract:", error);
  }
}

async function getBalance(address) {
  try {
    const balance = await Tezos.tz.getBalance(address);
    console.log("Balance:", balance.toNumber() / 1e6, "XTZ");
  } catch (error) {
    console.error("Error getting the balance:", error);
  }
}

// Sign a message for authentication (Sync Wallet)
async function signMessage() {
  const message = "Please sign this message to confirm your authenticity.";
  try {
    const signature = await wallet.signText(message);
    console.log("Signature:", signature);

    // Verify the signature and sync the wallet (e.g., update user data in your system)
    // ...
  } catch (error) {
    console.error("Error signing the message:", error);
  }
}

// Change Tezos node
function changeTezosNode() {
  const newNodeUrl = prompt("Please enter the new Tezos node URL:");
  if (newNodeUrl) {
    Tezos.setProvider({ rpc: newNodeUrl });
  }
  // Send the new RPC node URL to your backend
  jQuery.ajax({
    url: yourBackendRpcNodeEndpoint,
    type: 'POST',
    data: {
        action: 'tezos_wp_plugin_rpc_node',
        new_rpc_node: newNodeUrl,
        security: rpcNodeNonce,
    },
    success: function(response) {
        if (response.success) {
            alert('RPC node URL updated successfully');
        } else {
            alert('Error updating RPC node URL');
        }
    },
    error: function(jqXHR, textStatus, errorThrown) {
        console.error('Error updating RPC node URL:', textStatus, errorThrown);
    },
  });

}

// Disconnect the wallet and update the UI
function disconnectWallet() {
  wallet.clearActiveAccount().then(() => {
    document.getElementById("connect-wallet").style.display = "block";
    document.getElementById("wallet-actions").style.display = "none";
    document.getElementById("sync-wallet").style.display = "none";
  });
}

// Update the UI after connecting the wallet
function updateUIAfterWalletConnection() {
  document.getElementById("connect-wallet").style.display = "none";
  document.getElementById("wallet-actions").style.display = "block";
  document.getElementById("sync-wallet").style.display = "block";
}

// Update the connectWallet function to call updateUIAfterWalletConnection
async function connectWallet() {
  try {
    const address = await wallet.client.getActiveAccount();
    if (!address) {
      await wallet.requestPermissions();
    }
    const userAddress = await wallet.getPKH();
    console.log("Connected wallet address:", userAddress);
  } catch (error) {
    console.error("Error connecting to the wallet:", error);
  }

  // Add this line after getting the user address (inside the try block)
  updateUIAfterWalletConnection();
}

// Define the baker's address and name
const bakerName = "FlipGoal Corporate Baker";

// Initiate DAppClient
const delegate_address = tezos_wp_plugin_vars.delegate_baker_address;
const client = wallet.client;


const delegate = () => {  
  client.requestOperation({
        operationDetails: [
            {
                kind: OpKind.DELEGATION,
                delegate: delegate_address,
            },
        ],
    });
};

// Add event listener to the button
document.getElementById("tezos-delegate-button").addEventListener("click", () => {
    // Check if we have an active account
    client.getActiveAccount().then((activeAccount) => {
        if (activeAccount) {
            // If we have an active account, send the delegate operation directly
            delegate();
        } else {
            // If we don't have an active account, we need to request permissions first and then send the delegate operation
            client.requestPermissions().then((permissions) => {
                delegate();
            });
        }
    });
});




// Add the new JavaScript functions to the global window object (for use in HTML event handlers)
window.connectWallet = connectWallet;
window.signMessage = signMessage;
window.changeTezosNode = changeTezosNode;
window.disconnectWallet = disconnectWallet;

