FROM node:18

# Setta la working directory
WORKDIR /app

# Copia package.json e lockfile
COPY package*.json ./

# Installa le dipendenze
RUN npm install

# Copia il resto del codice
COPY . .

# Espone la porta usata da Nuxt in dev
EXPOSE 3000

# Comando per avviare Nuxt in modalità sviluppo (con HMR)
CMD ["npm", "run", "dev"]
